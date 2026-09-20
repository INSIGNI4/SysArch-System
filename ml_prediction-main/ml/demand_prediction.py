import os
import sys
import joblib
import numpy as np
import pandas as pd
import mysql.connector

from xgboost import XGBRegressor
from sklearn.metrics import mean_absolute_error, mean_squared_error

sys.path.append(os.path.dirname(os.path.abspath(__file__)))
# from config import (
#     DB_CONFIG,
#     FORECAST_DAYS,
#     MIN_HISTORY_DAYS,
#     SALES_TABLE,
#     PRODUCT_TABLE,
#     SALES_DATE_COLUMN,
#     SALES_PRODUCT_ID_COLUMN,
#     SALES_QUANTITY_COLUMN,
#     PRODUCT_ID_COLUMN,
#     PRODUCT_NAME_COLUMN,
#     PRODUCT_CATEGORY_COLUMN,
# )

from config import (
    DB_CONFIG,
    FORECAST_DAYS,
    MIN_HISTORY_DAYS,
    SALES_TABLE,
    SALES_ITEM_TABLE,
    PRODUCT_TABLE,
    SALES_ID_COLUMN,
    SALES_DATE_COLUMN,
    SALES_ITEM_PRODUCT_ID_COLUMN,
    SALES_ITEM_QUANTITY_COLUMN,
    PRODUCT_ID_COLUMN,
    PRODUCT_NAME_COLUMN,
    PRODUCT_CATEGORY_COLUMN,
)

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH = os.path.join(BASE_DIR, "xgboost_demand_model.joblib")

def get_connection():
    return mysql.connector.connect(**DB_CONFIG)

# def load_sales():
#     conn = get_connection()

#     query = f"""
#         SELECT
#             s.{SALES_PRODUCT_ID_COLUMN} AS Product_ID,
#             DATE(s.{SALES_DATE_COLUMN}) AS SalesDate,
#             SUM(s.{SALES_QUANTITY_COLUMN}) AS Quantity_Sold
#         FROM {SALES_TABLE} s
#         GROUP BY s.{SALES_PRODUCT_ID_COLUMN}, DATE(s.{SALES_DATE_COLUMN})
#         ORDER BY DATE(s.{SALES_DATE_COLUMN})
#     """

#     sales = pd.read_sql(query, conn)
#     conn.close()

#     sales["SalesDate"] = pd.to_datetime(sales["SalesDate"])
#     sales["Quantity_Sold"] = pd.to_numeric(
#         sales["Quantity_Sold"], errors="coerce"
#     ).fillna(0)

#     return sales


def load_sales():
    conn = get_connection()

    query = f"""
        SELECT
            si.{SALES_ITEM_PRODUCT_ID_COLUMN} AS Product_ID,
            DATE(s.{SALES_DATE_COLUMN}) AS SalesDate,
            SUM(si.{SALES_ITEM_QUANTITY_COLUMN}) AS Quantity_Sold
        FROM {SALES_TABLE} s
        INNER JOIN {SALES_ITEM_TABLE} si
            ON s.{SALES_ID_COLUMN} = si.{SALES_ID_COLUMN}
        GROUP BY
            si.{SALES_ITEM_PRODUCT_ID_COLUMN},
            DATE(s.{SALES_DATE_COLUMN})
        ORDER BY
            DATE(s.{SALES_DATE_COLUMN})
    """

    sales = pd.read_sql(query, conn)
    conn.close()

    sales["SalesDate"] = pd.to_datetime(sales["SalesDate"])

    sales["Quantity_Sold"] = pd.to_numeric(
        sales["Quantity_Sold"],
        errors="coerce"
    ).fillna(0)

    return sales

def load_products():
    conn = get_connection()

    query = f"""
        SELECT
            {PRODUCT_ID_COLUMN} AS Product_ID,
            {PRODUCT_NAME_COLUMN} AS ProductName,
            {PRODUCT_CATEGORY_COLUMN} AS Category
        FROM {PRODUCT_TABLE}
    """

    products = pd.read_sql(query, conn)
    conn.close()

    products["Product_ID"] = products["Product_ID"].astype(str)
    return products

def prepare_daily_data(sales, products):
    sales["Product_ID"] = sales["Product_ID"].astype(str)

    data = sales.merge(products, on="Product_ID", how="left")
    data["ProductName"] = data["ProductName"].fillna(data["Product_ID"])
    data["Category"] = data["Category"].fillna("Uncategorized")

    end_date = data["SalesDate"].max()

    all_rows = []

    for product_id, group in data.groupby("Product_ID"):
        group = group.sort_values("SalesDate").copy()
        product_name = group["ProductName"].iloc[0]
        category = group["Category"].iloc[0]

        date_range = pd.date_range(
            group["SalesDate"].min(),
            end_date,
            freq="D"
        )

        daily = (
            group.groupby("SalesDate")["Quantity_Sold"]
            .sum()
            .reindex(date_range, fill_value=0)
            .rename_axis("SalesDate")
            .reset_index()
        )

        daily["Product_ID"] = product_id
        daily["ProductName"] = product_name
        daily["Category"] = category
        all_rows.append(daily)

    return pd.concat(all_rows, ignore_index=True)

def add_features(data):
    data = data.sort_values(["Product_ID", "SalesDate"]).copy()

    data["DayOfWeek"] = data["SalesDate"].dt.dayofweek
    data["DayOfMonth"] = data["SalesDate"].dt.day
    data["Month"] = data["SalesDate"].dt.month
    data["WeekOfYear"] = data["SalesDate"].dt.isocalendar().week.astype(int)
    data["IsWeekend"] = (data["DayOfWeek"] >= 5).astype(int)

    grouped = data.groupby("Product_ID")["Quantity_Sold"]

    data["Lag_1"] = grouped.shift(1)
    data["Lag_7"] = grouped.shift(7)
    data["Lag_14"] = grouped.shift(14)
    data["Lag_28"] = grouped.shift(28)

    data["Rolling_7"] = grouped.transform(
        lambda x: x.shift(1).rolling(7).mean()
    )
    data["Rolling_14"] = grouped.transform(
        lambda x: x.shift(1).rolling(14).mean()
    )
    data["Rolling_28"] = grouped.transform(
        lambda x: x.shift(1).rolling(28).mean()
    )

    return data

def train_model(data):
    feature_columns = [
        "DayOfWeek",
        "DayOfMonth",
        "Month",
        "WeekOfYear",
        "IsWeekend",
        "Lag_1",
        "Lag_7",
        "Lag_14",
        "Lag_28",
        "Rolling_7",
        "Rolling_14",
        "Rolling_28",
    ]

    train = data.dropna(subset=feature_columns + ["Quantity_Sold"]).copy()

    if len(train) < 50:
        raise ValueError(
            "Not enough historical data after feature preparation. "
            "At least 30 days per product is recommended."
        )

    cutoff = train["SalesDate"].quantile(0.80)

    train_part = train[train["SalesDate"] <= cutoff]
    test_part = train[train["SalesDate"] > cutoff]

    X_train = train_part[feature_columns]
    y_train = train_part["Quantity_Sold"]

    X_test = test_part[feature_columns]
    y_test = test_part["Quantity_Sold"]

    model = XGBRegressor(
        objective="reg:squarederror",
        n_estimators=400,
        max_depth=6,
        learning_rate=0.05,
        subsample=0.8,
        colsample_bytree=0.8,
        min_child_weight=3,
        random_state=42,
        n_jobs=-1,
    )

    model.fit(X_train, y_train)

    metrics = {}

    if len(test_part) > 0:
        prediction = model.predict(X_test)
        metrics["MAE"] = float(mean_absolute_error(y_test, prediction))
        metrics["RMSE"] = float(
            np.sqrt(mean_squared_error(y_test, prediction))
        )

    joblib.dump(
        {
            "model": model,
            "features": feature_columns,
        },
        MODEL_PATH
    )

    return model, feature_columns, metrics

def recursive_forecast(data, model, feature_columns):
    results = []

    for product_id, group in data.groupby("Product_ID"):
        history = group.sort_values("SalesDate").copy()

        if len(history) < MIN_HISTORY_DAYS:
            continue

        product_name = history["ProductName"].iloc[0]
        category = history["Category"].iloc[0]

        values = history["Quantity_Sold"].astype(float).tolist()
        last_date = history["SalesDate"].max()

        for step in range(1, FORECAST_DAYS + 1):
            forecast_date = last_date + pd.Timedelta(days=step)

            lag_1 = values[-1]
            lag_7 = values[-7] if len(values) >= 7 else np.mean(values)
            lag_14 = values[-14] if len(values) >= 14 else np.mean(values)
            lag_28 = values[-28] if len(values) >= 28 else np.mean(values)

            row = pd.DataFrame([{
                "DayOfWeek": forecast_date.dayofweek,
                "DayOfMonth": forecast_date.day,
                "Month": forecast_date.month,
                "WeekOfYear": int(forecast_date.isocalendar().week),
                "IsWeekend": int(forecast_date.dayofweek >= 5),
                "Lag_1": lag_1,
                "Lag_7": lag_7,
                "Lag_14": lag_14,
                "Lag_28": lag_28,
                "Rolling_7": np.mean(values[-7:]),
                "Rolling_14": np.mean(values[-14:]),
                "Rolling_28": np.mean(values[-28:]),
            }])

            prediction = float(model.predict(row[feature_columns])[0])
            prediction = max(0, prediction)

            values.append(prediction)

            results.append({
                "Product_ID": product_id,
                "ProductName": product_name,
                "Category": category,
                "ForecastDate": forecast_date.date(),
                "Predicted_Demand": round(prediction, 2),
            })

    return pd.DataFrame(results)

def save_predictions(predictions):
    conn = get_connection()
    cursor = conn.cursor()

    cursor.execute("""
        CREATE TABLE IF NOT EXISTS ml_predictions (
            Prediction_ID INT AUTO_INCREMENT PRIMARY KEY,
            Product_ID VARCHAR(100) NOT NULL,
            ProductName VARCHAR(255) NOT NULL,
            Category VARCHAR(255) NOT NULL,
            ForecastDate DATE NOT NULL,
            Predicted_Demand DECIMAL(12,2) NOT NULL,
            CreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_product_date (Product_ID, ForecastDate),
            INDEX idx_category (Category),
            INDEX idx_forecast_date (ForecastDate)
        )
    """)

    cursor.execute("DELETE FROM ml_predictions")

    insert_sql = """
        INSERT INTO ml_predictions
        (Product_ID, ProductName, Category, ForecastDate, Predicted_Demand)
        VALUES (%s, %s, %s, %s, %s)
    """

    rows = [
        (
            row.Product_ID,
            row.ProductName,
            row.Category,
            row.ForecastDate,
            float(row.Predicted_Demand),
        )
        for row in predictions.itertuples(index=False)
    ]

    cursor.executemany(insert_sql, rows)
    conn.commit()

    cursor.close()
    conn.close()

def main():
    print("Loading sales data...")
    sales = load_sales()

    if sales.empty:
        raise ValueError("No sales records were found.")

    print("Loading products...")
    products = load_products()

    print("Preparing daily product demand...")
    daily = prepare_daily_data(sales, products)

    print("Creating forecasting features...")
    featured = add_features(daily)

    print("Training XGBoost...")
    model, feature_columns, metrics = train_model(featured)

    print("Generating future product demand...")
    predictions = recursive_forecast(
        featured,
        model,
        feature_columns
    )

    if predictions.empty:
        raise ValueError(
            "No predictions were generated. Check product sales history."
        )

    print("Saving predictions to MySQL...")
    save_predictions(predictions)

    print("Prediction completed.")
    print(f"Predicted rows: {len(predictions)}")

    if metrics:
        print(f"MAE: {metrics['MAE']:.2f}")
        print(f"RMSE: {metrics['RMSE']:.2f}")




        
# if __name__ == "__main__":
#     print("Testing database connection...")

#     sales = load_sales()

#     print("\nNumber of sales rows:", len(sales))

#     products = load_products()

#     print("Number of product rows:", len(products))

#     daily_data = prepare_daily_data(sales, products)

#     print("Number of daily rows:", len(daily_data))

#     featured_data = add_features(daily_data)

#     print("\nFeatured data:")
#     print(featured_data.head(50))

#     print("\nNumber of featured rows:", len(featured_data))



if __name__ == "__main__":
    main()
# if __name__ == "__main__":
#     main()

#load product


# if __name__ == "__main__":
#     print("Testing database connection...")
    
#     sales = load_sales()

#     print("\nSales data:")
#     print(sales.head(20))

#     print("\nNumber of rows:", len(sales))


#load sales

# if __name__ == "__main__":
#     print("Testing database connection...")

#     sales = load_sales()

#     print("\nSales data:")
#     print(sales.head(20))

#     print("\nNumber of sales rows:", len(sales))

#     products = load_products()

#     print("\nProduct data:")
#     print(products.head(20))

#     print("\nNumber of product rows:", len(products))


#daily data

# if __name__ == "__main__":
#     print("Testing database connection...")

#     sales = load_sales()

#     print("\nSales data:")
#     print(sales.head(20))

#     print("\nNumber of sales rows:", len(sales))

#     products = load_products()

#     print("\nProduct data:")
#     print(products.head(20))

#     print("\nNumber of product rows:", len(products))

#     daily_data = prepare_daily_data(sales, products)

#     print("\nDaily data:")
#     print(daily_data.head(30))

#     print("\nNumber of daily rows:", len(daily_data))

#add feature 

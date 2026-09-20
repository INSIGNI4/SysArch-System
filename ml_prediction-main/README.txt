DEMAND PREDICTION INTEGRATION
PHP + MySQL + Python + XGBoost

WHAT THIS PACKAGE DOES

1. Reads historical sales directly from MySQL.
2. Groups sales by product and date.
3. Creates daily demand records, including zero-sales days.
4. Creates time-series features:
   - day of week
   - day of month
   - month
   - week of year
   - weekend indicator
   - 1-day lag
   - 7-day lag
   - 14-day lag
   - 28-day lag
   - 7-day rolling average
   - 14-day rolling average
   - 28-day rolling average
5. Trains an XGBoost regression model.
6. Evaluates the model using a chronological 80/20 split.
7. Predicts the next 7 days for every product with enough history.
8. Saves predictions into ml_predictions.
9. PHP can retrieve product, category, and daily forecast data for charts.

IMPORTANT DATABASE SETUP

Open ml/config.py and change:

DB_CONFIG["database"]

Also verify these column names:

SALES_TABLE = "sales"
PRODUCT_TABLE = "product"

SALES_DATE_COLUMN = "SalesDate"
SALES_PRODUCT_ID_COLUMN = "Product_ID"
SALES_QUANTITY_COLUMN = "Quantity"

PRODUCT_ID_COLUMN = "Product_ID"
PRODUCT_NAME_COLUMN = "ProductName"
PRODUCT_CATEGORY_COLUMN = "Type"

If your sales quantity column is named UnitSold, change:

SALES_QUANTITY_COLUMN = "UnitSold"

If Product_ID or another field has a different name, change it in config.py.

INSTALL PYTHON PACKAGES

Open Command Prompt in this folder:

pip install -r requirements.txt

TEST THE MODEL

Run:

python ml/demand_prediction.py

Expected process:

Loading sales data...
Loading products...
Preparing daily product demand...
Creating forecasting features...
Training XGBoost...
Generating future product demand...
Saving predictions to MySQL...
Prediction completed.

PHP INTEGRATION

run_prediction.php starts the Python model.

Your admin page can call:

run_prediction.php

After it completes, the prediction data is in:

ml_predictions

DASHBOARD DATA

Product demand:

dashboardQuery/get_demand_predictions.php?mode=product

Category demand:

dashboardQuery/get_demand_predictions.php?mode=category

7-day demand trend:

dashboardQuery/get_demand_predictions.php?mode=trend

CHART.JS

Make sure Chart.js is already loaded on your dashboard.

Then include:

<script src="dashboardAssets/js/demand_dashboard.js"></script>

Create a canvas:

<canvas id="productDemandChart"></canvas>

Then call:

loadDemandChart("productDemandChart", "product");

For category:

loadDemandChart("categoryDemandChart", "category");

For forecast trend:

loadDemandChart("demandTrendChart", "trend");

RECOMMENDED THESIS FLOW

MySQL sales data
    ->
Python preprocessing
    ->
Feature engineering
    ->
XGBoost
    ->
Product demand prediction
    ->
ml_predictions
    ->
PHP
    ->
Dashboard charts

CATEGORY PREDICTION

The model predicts each product first.

Category demand is calculated by summing the product predictions.

Example:

Coffee = 128
Soft Drink = 143
Juice = 72

Beverage = 343

This avoids needing a separate category ML model.

IMPORTANT

This package assumes the sales table contains one or more sales records with:

Product ID
Sales Date
Quantity

If your actual sales table has different fields, only the SQL/query configuration needs to be adjusted.

The model is designed for demand quantity, not revenue.

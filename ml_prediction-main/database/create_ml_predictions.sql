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
);

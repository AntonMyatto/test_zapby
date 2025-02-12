SELECT ph.product_id, ph.price
FROM price_history ph
JOIN (
    SELECT product_id, MAX(created_at) AS latest_date
    FROM price_history
    WHERE created_at <= '2024-03-05'
    GROUP BY product_id
) latest_prices
ON ph.product_id = latest_prices.product_id
AND ph.created_at = latest_prices.latest_date;

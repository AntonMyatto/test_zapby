SELECT price
FROM price_history
WHERE product_id = 1
  AND created_at = (
      SELECT MAX(created_at)
      FROM price_history
      WHERE product_id = 1 AND created_at <= '2024-03-05'
  );

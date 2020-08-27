/*
а) написать запрос, который бы выводил полное имя и баланс человека на данный момент
*/
SELECT p.id, p.fullname, (COALESCE(SUM(t2.amount), 0) - COALESCE(SUM(t.amount), 0) + 100) as balance
FROM persons as p
         LEFT OUTER JOIN transactions as t on t.from_person_id = p.id
         LEFT OUTER JOIN transactions as t2 ON t2.to_person_id = p.id
GROUP BY p.id;

/*Через функциию*/
DROP FUNCTION IF EXISTS balance_calc;
DELIMITER $$
CREATE FUNCTION balance_calc(person_id INT) RETURNS DECIMAL(11,2)
BEGIN
    DECLARE start_balance DECIMAL(11,2) default 100;
    DECLARE income DECIMAL(11,2) default 0;
    DECLARE expense DECIMAL(11,2) default 0;

    SELECT COALESCE(sum(amount), 0) INTO income FROM transactions WHERE to_person_id=person_id;
    SELECT COALESCE(sum(amount), 0)  INTO expense FROM transactions WHERE from_person_id=person_id;

    RETURN start_balance + income - expense;
END
$$
SELECT fullname, balance_calc(`id`) FROM persons;

/*
б) написать запрос, который бы выводил город, представители которого участвовали в передаче денег наибольшее количество раз
*/
SELECT c.name
FROM transactions as t
         JOIN persons as p ON t.from_person_id = p.id
         JOIN cities c on p.city_id = c.id
GROUP BY c.name
ORDER BY COUNT(t.transaction_id) DESC
LIMIT 1

/*
в) написать запрос, отражающий все транзакции, где передача денег осуществлялась между представителями одного города
*/
SELECT t.*
FROM transactions as t
         JOIN persons as p ON t.from_person_id = p.id
         JOIN persons as p2 ON t.to_person_id = p2.id
WHERE p.city_id = p2.city_id
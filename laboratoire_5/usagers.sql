USE tch056_labo_forum;

CREATE TABLE usagers (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(500) NOT NULL
);

INSERT INTO usagers (username, password) VALUES ('alice', '$2y$04$ZQ1y3zCsQ/atystVX3pkPe1v2DmQE66bfMJlg07Hwswakh48HG8wm'); --alice123
INSERT INTO usagers (username, password) VALUES ('bob', '$2y$04$4at1bsWCG7o5i.BjNhpoiePv/uBISzz9LfCTeQFBquy.uhfDsP44u'); --bob123
INSERT INTO usagers (username, password) VALUES ('charlie', '$2y$04$UI2zS3YULzxOdEcWgx11/.oSbrZQyhes7CrzcuhEvrN7pEuyfG6Km'); --charlie123
INSERT INTO usagers (username, password) VALUES ('david', '$2y$04$q7fABBkC5/GClkYCWO6CO.9KUqwVD2bMJxdUeMI.l8tJqysFHiSBG'); --david123
INSERT INTO usagers (username, password) VALUES ('eve', '$2y$04$rAroBNdLThTZI9X6yotjaO5FpBxUFqfLcJ9dbLYzikH51c0pSpJK.'); --eve123




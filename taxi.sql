-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3307
-- Время создания: Окт 18 2023 г., 19:22
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `taxi`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`root`@`%` PROCEDURE `GetCarInfoByModel` (IN `selectedCarModel` VARCHAR(100))   BEGIN
    SELECT Colors.ColorName, Cars.CarNumber, Drivers.Name, Drivers.Rating, Drivers.DriverID
    FROM Cars
    LEFT JOIN Drivers ON Cars.CarID = Drivers.CarID
    LEFT JOIN Colors ON Colors.ColorID = Cars.ColorID
    WHERE Cars.CarModel = selectedCarModel;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `GetOrderInfoByCarName` (IN `selectedCarModel` VARCHAR(100))   BEGIN
    SELECT Colors.ColorName, Cars.CarNumber, Drivers.Name, Drivers.Rating, Drivers.DriverID
    FROM Cars
    LEFT JOIN Drivers ON Cars.CarID = Drivers.CarID
    LEFT JOIN Colors ON Colors.ColorID = Cars.ColorID
    WHERE Cars.CarModel = selectedCarModel;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `GetOrdersByDriverID` (IN `userID` INT)   BEGIN
    SELECT * FROM Orders
    LEFT JOIN PaymentMethods ON Orders.PaymentMethodID = PaymentMethods.PaymentMethodID
    left join Drivers on Orders.DriverID = Drivers.DriverID 
    WHERE Drivers.UserID = userID;
end$$

CREATE DEFINER=`root`@`%` PROCEDURE `GetOrdersByUserID` (IN `userID` INT)   BEGIN
    SELECT * FROM Orders
    LEFT JOIN PaymentMethods ON Orders.PaymentMethodID = PaymentMethods.PaymentMethodID
    join Drivers on Drivers.DriverID = Orders.DriverID
    WHERE Orders.UserID = userID;
end$$

CREATE DEFINER=`root`@`%` PROCEDURE `GetProfileInfoByUsername` (IN `username` VARCHAR(255))   BEGIN
    SELECT Users.*, Drivers.*, Cars.*, CarClasses.*, Colors.*
    FROM Users
    LEFT JOIN Drivers ON Users.UserID = Drivers.UserID
    LEFT JOIN Cars ON Drivers.CarID = Cars.CarID
    LEFT JOIN CarClasses ON Cars.CarClassID = CarClasses.CarClassID
    LEFT JOIN Colors ON Cars.ColorID = Colors.ColorID
    WHERE Users.username = username;
end$$

--
-- Функции
--
CREATE DEFINER=`root`@`%` FUNCTION `CarModelsByComfortClass` (`selectedCarClass` VARCHAR(100)) RETURNS VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
    DECLARE carModels VARCHAR(100);

    SELECT group_concat(CarModel) INTO carModels
    FROM Cars
    JOIN CarClasses ON Cars.CarClassID = CarClasses.CarClassID
    WHERE CarClasses.ClassName = selectedCarClass;

    RETURN carModels;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `GetAdminByUsernameAndPassword` (`us_n` VARCHAR(100), `pass` VARCHAR(100)) RETURNS INT  BEGIN
    DECLARE user_id INT;
    SELECT UserID INTO user_id
    FROM Users
    WHERE Username = us_n AND Password = pass AND UserTypeID = 3;
    RETURN user_id;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `GetCarDataByModel` (`selectedCarModel` VARCHAR(100)) RETURNS LONGTEXT CHARSET utf8mb4 COLLATE utf8mb4_bin  BEGIN
    DECLARE carData JSON;
    
    SET carData = (
        SELECT JSON_ARRAYAGG(
            JSON_OBJECT(
                'ColorName', Colors.ColorName,
                'CarNumber', Cars.CarNumber,
                'Name', Drivers.Name,
                'Rating', Drivers.Rating,
                'DriverID', Drivers.DriverID
            )
        )
        FROM Cars
        LEFT JOIN Drivers ON Cars.CarID = Drivers.CarID
        LEFT JOIN Colors ON Colors.ColorID = Cars.ColorID
        WHERE Cars.CarModel = selectedCarModel
    );

    RETURN carData;
end$$

CREATE DEFINER=`root`@`%` FUNCTION `GetDriverByUsernameAndPassword` (`us_n` VARCHAR(100), `pass` VARCHAR(100)) RETURNS INT  BEGIN
    DECLARE user_id INT;
    SELECT UserID INTO user_id
    FROM Users
    WHERE Username = us_n AND Password = pass AND UserTypeID = 2;
    RETURN user_id;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `GetInfoByCarName` (`selectedCarModel` VARCHAR(100)) RETURNS LONGTEXT CHARSET utf8mb4 COLLATE utf8mb4_bin  BEGIN
    DECLARE carData JSON;
    
    SET carData = (
        SELECT JSON_ARRAYAGG(
            JSON_OBJECT(
                'ColorName', Colors.ColorName,
                'CarNumber', Cars.CarNumber,
                'Name', Drivers.Name,
                'Rating', Drivers.Rating,
                'DriverID', Drivers.DriverID
            )
        )
        FROM Cars
        LEFT JOIN Drivers ON Cars.CarID = Drivers.CarID
        LEFT JOIN Colors ON Colors.ColorID = Cars.ColorID
        WHERE Cars.CarModel = selectedCarModel
    );

    RETURN carData;
end$$

CREATE DEFINER=`root`@`%` FUNCTION `GetUserByUsernameAndPassword` (`us_n` VARCHAR(100), `pass` VARCHAR(100)) RETURNS INT  BEGIN
    DECLARE user_id INT;
    SELECT UserID INTO user_id
    FROM Users
    WHERE Username = us_n AND Password = pass AND UserTypeID = 1;
    RETURN user_id;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `GetUserInRegistration` (`us_n` VARCHAR(100)) RETURNS INT  BEGIN
    DECLARE user_id INT;
    SELECT UserID INTO user_id
    FROM Users
    WHERE Username = us_n AND UserTypeID = 1;
    RETURN user_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `administrators`
--

CREATE TABLE `administrators` (
  `AdminID` int NOT NULL,
  `UserID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `administrators`
--

INSERT INTO `administrators` (`AdminID`, `UserID`) VALUES
(1, 8),
(2, 11);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `allcarclasses`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `allcarclasses` (
`ClassName` varchar(100)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `allcarsclasses`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `allcarsclasses` (
`ClassName` varchar(100)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `allclients`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `allclients` (
`Email` varchar(100)
,`Password` varchar(100)
,`Phone` varchar(100)
,`UserID` int
,`Username` varchar(100)
,`UserTypeID` int
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `alldriversratings`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `alldriversratings` (
`DriverID` int
,`Rating` float
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `alldriverswiththeircars`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `alldriverswiththeircars` (
`CarModel` varchar(100)
,`CarNumber` varchar(100)
,`ClassName` varchar(100)
,`ColorName` varchar(100)
,`Email` varchar(100)
,`LicenseNum` int
,`Name` varchar(100)
,`Passport` varchar(100)
,`Password` varchar(100)
,`Phone` varchar(100)
,`Rating` float
,`Username` varchar(100)
,`VIN` varchar(100)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `allorders`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `allorders` (
`Amount` float
,`BabyChair` tinyint(1)
,`DriverID` int
,`EndLocation` varchar(100)
,`Mark` float
,`OrderID` int
,`PaymentMethodID` int
,`SilentDriver` tinyint(1)
,`StartLocation` varchar(100)
,`UserID` int
);

-- --------------------------------------------------------

--
-- Структура таблицы `carclasses`
--

CREATE TABLE `carclasses` (
  `CarClassID` int NOT NULL,
  `ClassName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `carclasses`
--

INSERT INTO `carclasses` (`CarClassID`, `ClassName`) VALUES
(1, 'Эконом'),
(2, 'Комфорт'),
(3, 'Комфорт Плюс'),
(4, 'Бизнес');

-- --------------------------------------------------------

--
-- Структура таблицы `cars`
--

CREATE TABLE `cars` (
  `CarID` int NOT NULL,
  `VIN` varchar(100) NOT NULL,
  `CarModel` varchar(100) NOT NULL,
  `CarNumber` varchar(100) NOT NULL,
  `ColorID` int NOT NULL,
  `CarClassID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `cars`
--

INSERT INTO `cars` (`CarID`, `VIN`, `CarModel`, `CarNumber`, `ColorID`, `CarClassID`) VALUES
(1, '8675843', 'Renault Logan', 'C680EX', 1, 1),
(2, '7659847', 'Volkswagen Polo', 'O438BC', 2, 1),
(3, '1236543', 'Skoda Rapid', 'P786HB', 3, 1),
(4, '7685446', 'Kia Rio', 'E121XX', 7, 1),
(5, '1239087', 'Hyundai Sonata', 'C435EX', 4, 2),
(6, '5467679', 'Skoda Octavia', 'C295OY', 5, 2),
(7, '3453432', 'Volkswagen Jetta', 'P434HB', 2, 2),
(8, '8789995', 'Toyota Camry', 'O133BC', 5, 3),
(9, '5438754', 'Kia K5', 'P871TT', 6, 3),
(10, '5932871', 'Honda Legend', 'T454CP', 1, 3),
(11, '1986543', 'Mazda CX-9', 'Y719EB', 2, 3),
(12, '2343769', 'Audi A6', 'Y141BM', 8, 4),
(13, '4360980', 'BMW 7er', 'T545CA', 2, 4),
(14, '2109086', 'Mercedes-Benz S-class', 'A555BC', 1, 4),
(15, '6549830', 'Audi S8', 'T777PY', 4, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `colors`
--

CREATE TABLE `colors` (
  `ColorID` int NOT NULL,
  `ColorName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `colors`
--

INSERT INTO `colors` (`ColorID`, `ColorName`) VALUES
(1, 'чёрный'),
(2, 'белый'),
(3, 'бежевый'),
(4, 'синий'),
(5, 'жёлтый'),
(6, 'красный'),
(7, 'зелёный'),
(8, 'серый');

-- --------------------------------------------------------

--
-- Структура таблицы `drivers`
--

CREATE TABLE `drivers` (
  `DriverID` int NOT NULL,
  `LicenseNum` int NOT NULL,
  `Passport` varchar(100) NOT NULL,
  `Rating` float NOT NULL DEFAULT '5',
  `UserID` int NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Photo` varchar(100) DEFAULT NULL,
  `CarID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `drivers`
--

INSERT INTO `drivers` (`DriverID`, `LicenseNum`, `Passport`, `Rating`, `UserID`, `Name`, `Photo`, `CarID`) VALUES
(1, 45583475, '40405050', 4.25, 15, 'Антон', NULL, 10),
(2, 18756483, '40 16 675 344', 4.5, 5, 'Владислав', NULL, 2),
(3, 18128768, '40 24 564 890', 5, 6, 'Алексей', NULL, 14),
(4, 18987865, '40 17 685 362', 3, 7, 'Иван', NULL, 9),
(5, 18546734, '5050505', 5, 16, 'Григорий', NULL, 5),
(8, 2, '2', 2.5, 2, '2', NULL, 3),
(9, 19756432, '40226574321', 4.5, 26, 'Анна', NULL, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `OrderID` int NOT NULL,
  `UserID` int NOT NULL,
  `DriverID` int NOT NULL,
  `StartLocation` varchar(100) NOT NULL,
  `EndLocation` varchar(100) NOT NULL,
  `Amount` float DEFAULT NULL,
  `BabyChair` tinyint(1) DEFAULT '0',
  `SilentDriver` tinyint(1) DEFAULT '0',
  `PaymentMethodID` int NOT NULL,
  `Mark` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `DriverID`, `StartLocation`, `EndLocation`, `Amount`, `BabyChair`, `SilentDriver`, `PaymentMethodID`, `Mark`) VALUES
(3, 14, 2, 'nkjl', 'njk', 0, NULL, NULL, 1, 4),
(4, 14, 3, 'Политехническая 1', 'Обручевых 1', 0, NULL, NULL, 1, NULL),
(5, 14, 2, 'Невский пр. 34', 'Садовая 5', 0, 1, 1, 2, NULL),
(6, 3, 3, 'Пионерская, 26', 'метро Василеостровская', 0, NULL, 1, 1, 5),
(7, 14, 3, 'Санкт-Петербург', 'никуда', 0, 1, 1, 2, NULL),
(9, 10, 5, 'Янино', 'Санкт-Петербург', 0, 1, NULL, 2, NULL),
(10, 10, 4, 'метро Удельная', 'Обручевых 1', 0, 1, 1, 2, NULL),
(12, 3, 5, 'метро Спортивная', 'метро Василеостровская', 0, NULL, 1, 1, NULL),
(13, 3, 4, 'метро Политехническая', 'Обручевых 1', 0, NULL, 1, 1, 2),
(14, 3, 4, 'Обручевых 1', 'метро Озерки', 0, NULL, 1, 2, NULL),
(15, 3, 2, 'Красная площадь', 'Бауманская 1', NULL, NULL, 1, 1, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `paymentmethods`
--

CREATE TABLE `paymentmethods` (
  `PaymentMethodID` int NOT NULL,
  `PaymentMethodName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `paymentmethods`
--

INSERT INTO `paymentmethods` (`PaymentMethodID`, `PaymentMethodName`) VALUES
(1, 'Банковская карта'),
(2, 'Наличные');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `UserID` int NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Phone` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `UserTypeID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Phone`, `Email`, `Password`, `UserTypeID`) VALUES
(2, '2', '2', '2', '2', 2),
(3, 'Nastya26', '+79117658494', 'nastya2676090@mail.ru', '123', 1),
(5, 'Vlad', '+79116574833', 'vmr.dollarv@gmail.ru', '123', 2),
(6, 'Alex', '+79213453321', 'alex@mail.ru', '123', 2),
(7, 'Ivan', '+79214756453', 'ivan@yandex.ru', '123', 2),
(8, 'AnastasiaD', '+79116578899', 'nastya2676090@mail.ru', '123', 3),
(10, 'Olya', '+79214675588', 'olya@mail.ru', '123', 1),
(11, '3', '3', '3', '3', 3),
(14, '1', '1', '1', '1', 1),
(15, 'Ant', '+79314359909', 'ant@mail.ru', '123', 2),
(16, 'theDriver', '+79116547373', 'g@yandex.ru', '123', 2),
(19, 'Misha001', '+79526437464', 'misha@mail.ru', '123', 1),
(21, '5', '5', '5', '5', 1),
(22, 'Svetlana', '_78123647121', 'sv@mail.ru', '123', 1),
(23, 'Dmitriy012', '+78126574090', 'dmtr@yandex.ru', '123', 1),
(24, 'iRiNa712', '+79118980090', 'qwerty@mail.ru', '123', 1),
(25, 'MadMax', '+79217671213', 'madmax@mail.ru', '123', 1),
(26, 'Anna11', '+79117609234', 'an@yandex.ru', '123', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `usertypes`
--

CREATE TABLE `usertypes` (
  `UserTypeID` int NOT NULL,
  `UserTypeName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `usertypes`
--

INSERT INTO `usertypes` (`UserTypeID`, `UserTypeName`) VALUES
(1, 'user'),
(2, 'driver'),
(3, 'admin');

-- --------------------------------------------------------

--
-- Структура для представления `allcarclasses`
--
DROP TABLE IF EXISTS `allcarclasses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `allcarclasses`  AS SELECT `carclasses`.`ClassName` AS `ClassName` FROM `carclasses``carclasses`  ;

-- --------------------------------------------------------

--
-- Структура для представления `allcarsclasses`
--
DROP TABLE IF EXISTS `allcarsclasses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `allcarsclasses`  AS SELECT `carclasses`.`ClassName` AS `ClassName` FROM `carclasses``carclasses`  ;

-- --------------------------------------------------------

--
-- Структура для представления `allclients`
--
DROP TABLE IF EXISTS `allclients`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `allclients`  AS SELECT `users`.`UserID` AS `UserID`, `users`.`Username` AS `Username`, `users`.`Phone` AS `Phone`, `users`.`Email` AS `Email`, `users`.`Password` AS `Password`, `users`.`UserTypeID` AS `UserTypeID` FROM `users` WHERE (`users`.`UserTypeID` = 1)  ;

-- --------------------------------------------------------

--
-- Структура для представления `alldriversratings`
--
DROP TABLE IF EXISTS `alldriversratings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `alldriversratings`  AS SELECT `drivers`.`DriverID` AS `DriverID`, `drivers`.`Rating` AS `Rating` FROM `drivers``drivers`  ;

-- --------------------------------------------------------

--
-- Структура для представления `alldriverswiththeircars`
--
DROP TABLE IF EXISTS `alldriverswiththeircars`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `alldriverswiththeircars`  AS SELECT `users`.`Username` AS `Username`, `users`.`Phone` AS `Phone`, `users`.`Email` AS `Email`, `drivers`.`Name` AS `Name`, `users`.`Password` AS `Password`, `drivers`.`LicenseNum` AS `LicenseNum`, `drivers`.`Passport` AS `Passport`, `drivers`.`Rating` AS `Rating`, `cars`.`VIN` AS `VIN`, `cars`.`CarModel` AS `CarModel`, `cars`.`CarNumber` AS `CarNumber`, `carclasses`.`ClassName` AS `ClassName`, `colors`.`ColorName` AS `ColorName` FROM ((((`users` join `drivers` on((`users`.`UserID` = `drivers`.`UserID`))) join `cars` on((`drivers`.`CarID` = `cars`.`CarID`))) join `carclasses` on((`cars`.`CarClassID` = `carclasses`.`CarClassID`))) join `colors` on((`cars`.`ColorID` = `colors`.`ColorID`))) WHERE (`users`.`UserTypeID` = 2)  ;

-- --------------------------------------------------------

--
-- Структура для представления `allorders`
--
DROP TABLE IF EXISTS `allorders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `allorders`  AS SELECT `orders`.`OrderID` AS `OrderID`, `orders`.`UserID` AS `UserID`, `orders`.`DriverID` AS `DriverID`, `orders`.`StartLocation` AS `StartLocation`, `orders`.`EndLocation` AS `EndLocation`, `orders`.`Amount` AS `Amount`, `orders`.`BabyChair` AS `BabyChair`, `orders`.`SilentDriver` AS `SilentDriver`, `orders`.`PaymentMethodID` AS `PaymentMethodID`, `orders`.`Mark` AS `Mark` FROM `orders``orders`  ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`AdminID`),
  ADD KEY `administrators_FK` (`UserID`);

--
-- Индексы таблицы `carclasses`
--
ALTER TABLE `carclasses`
  ADD PRIMARY KEY (`CarClassID`);

--
-- Индексы таблицы `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`CarID`),
  ADD KEY `Cars_FK` (`ColorID`),
  ADD KEY `Cars_FK_1` (`CarClassID`);

--
-- Индексы таблицы `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`ColorID`);

--
-- Индексы таблицы `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`DriverID`),
  ADD KEY `drivers_FK_1` (`CarID`),
  ADD KEY `drivers_FK` (`UserID`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `orders_FK_1` (`UserID`),
  ADD KEY `orders_FK_2` (`PaymentMethodID`),
  ADD KEY `orders_FK` (`DriverID`);

--
-- Индексы таблицы `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD PRIMARY KEY (`PaymentMethodID`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `users_FK` (`UserTypeID`);

--
-- Индексы таблицы `usertypes`
--
ALTER TABLE `usertypes`
  ADD PRIMARY KEY (`UserTypeID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `drivers`
--
ALTER TABLE `drivers`
  MODIFY `DriverID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `administrators`
--
ALTER TABLE `administrators`
  ADD CONSTRAINT `administrators_FK` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Ограничения внешнего ключа таблицы `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `Cars_FK` FOREIGN KEY (`ColorID`) REFERENCES `colors` (`ColorID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Cars_FK_1` FOREIGN KEY (`CarClassID`) REFERENCES `carclasses` (`CarClassID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_FK` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drivers_FK_1` FOREIGN KEY (`CarID`) REFERENCES `cars` (`CarID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_FK` FOREIGN KEY (`DriverID`) REFERENCES `drivers` (`DriverID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_FK_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_FK_2` FOREIGN KEY (`PaymentMethodID`) REFERENCES `paymentmethods` (`PaymentMethodID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_FK` FOREIGN KEY (`UserTypeID`) REFERENCES `usertypes` (`UserTypeID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

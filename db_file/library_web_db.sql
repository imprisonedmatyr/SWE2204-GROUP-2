-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2024 at 09:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_web_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `TITLE` varchar(255) NOT NULL,
  `AUTHOR` varchar(255) NOT NULL,
  `BOOK_COVER` varchar(255) NOT NULL,
  `STATUS` enum('available','UnAvailable') NOT NULL,
  `CATEGORY` varchar(255) NOT NULL,
  `GENRE` varchar(50) NOT NULL,
  `PUBLICATION YEAR` int(11) NOT NULL,
  `PAGES` int(11) DEFAULT NULL,
  `visits` int(11) DEFAULT 0,
  `short_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `TITLE`, `AUTHOR`, `BOOK_COVER`, `STATUS`, `CATEGORY`, `GENRE`, `PUBLICATION YEAR`, `PAGES`, `visits`, `short_description`) VALUES
(411, 'Great Expectations', 'Charles Dickens', 'GreatExpectations.jpg', 'available', 'Fiction', 'Classic', 1861, 544, 130, 'My_Frozen_turbulence.txt'),
(412, 'Naruto', 'Masashi Kishimoto', 'Naruto.jpg', 'available', 'Graphic Novels & Comics', 'Manga', 1999, 220, 238, NULL),
(413, 'One Piece', 'Eiichiro Oda', 'OnePiece.jpg', 'available', 'Graphic Novels & Comics', 'Manga', 1997, 978, 71, NULL),
(414, '1984', 'George Orwell', '1984.jpg', 'available', 'Fiction', 'Dystopian', 1949, 328, 21, NULL),
(415, 'Moby Dick', 'Herman Melville', 'MobyDick.jpg', 'available', 'Fiction', 'Adventure', 1851, 585, 4, NULL),
(416, 'Pride and Prejudice', 'Jane Austen', 'PrideAndPrejudice.jpg', 'available', 'Fiction', 'Romance', 1813, 432, 7, NULL),
(417, 'The Great Gatsby', 'F. Scott Fitzgerald', 'TheGreatGatsby.jpg', 'available', 'Fiction', 'Classic', 1925, 180, 2, NULL),
(418, 'Brave New World', 'Aldous Huxley', 'BraveNewWorld.jpg', 'available', 'Fiction', 'Dystopian', 1932, 268, 4, NULL),
(419, 'The Catcher in the Rye', 'J.D. Salinger', 'TheCatcherInTheRye.jpg', 'available', 'Fiction', 'Classic', 1951, 277, 228, NULL),
(420, 'To Kill a Mockingbird', 'Harper Lee', 'ToKillAMockingbird.jpg', 'available', 'Fiction', 'Classic', 1960, 281, 33, NULL),
(421, 'The Hobbit', 'J.R.R. Tolkien', 'TheHobbit.jpg', 'available', 'Fiction', 'Fantasy', 1937, 310, 52, NULL),
(422, 'Fahrenheit 451', 'Ray Bradbury', 'Fahrenheit451.jpg', 'available', 'Fiction', 'Dystopian', 1953, 158, 1, NULL),
(423, 'The Alchemist', 'Paulo Coelho', 'TheAlchemist.jpg', 'available', 'Fiction', 'Adventure', 1988, 208, 13, NULL),
(424, 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'HarryPotter.jpg', 'available', 'Fiction', 'Fantasy', 1997, 309, 0, NULL),
(425, 'War and Peace', 'Leo Tolstoy', 'WarAndPeace.jpg', 'available', 'Fiction', 'Historical', 1869, 1225, 7, NULL),
(426, 'The Picture of Dorian Gray', 'Oscar Wilde', 'ThePictureOfDorianGray.jpg', 'available', 'Fiction', 'Classic', 1890, 250, 4, NULL),
(427, 'Les Misérables', 'Victor Hugo', 'LesMiserables.jpg', 'available', 'Fiction', 'Historical', 1862, 1232, 0, NULL),
(428, 'The Grapes of Wrath', 'John Steinbeck', 'TheGrapesOfWrath.jpg', 'available', 'Fiction', 'Historical', 1939, 464, 2, NULL),
(429, 'The Fault in Our Stars', 'John Green', 'TheFaultInOurStars.jpg', 'available', 'Fiction', 'Young Adult', 2012, 313, 0, NULL),
(430, 'Catch-22', 'Joseph Heller', 'Catch22.jpg', 'available', 'Fiction', 'Fantasy', 1961, 453, 0, NULL),
(431, 'Game of Thrones', 'George R.R. Martin', 'GameOfThrones.jpg', 'available', 'Fiction', 'Fantasy', 1996, 694, 0, NULL),
(432, 'Chainsaw Man', 'Tatsuki Fujimoto', 'ChainsawMan.jpg', 'available', 'Graphic Novels & Comics', 'Action', 2018, 192, 9, NULL),
(433, 'The Da Vinci Code', 'Dan Brown', 'TheDaVinciCode.jpg', 'available', 'Fiction', 'Thriller', 2003, 489, 0, NULL),
(434, 'The Hunger Games', 'Suzanne Collins', 'TheHungerGames.jpg', 'available', 'Fiction', 'Fantasy', 2008, 374, 0, NULL),
(435, 'Little Fires Everywhere', 'Celeste Ng', 'LittleFiresEverywhere.jpg', 'available', 'Fiction', 'Contemporary', 2017, 350, 0, NULL),
(436, 'The Silent Patient', 'Alex Michaelides', 'TheSilentPatient.jpg', 'available', 'Fiction', 'Thriller', 2019, 336, 0, NULL),
(437, 'The Midnight Library', 'Matt Haig', 'TheMidnightLibrary.jpg', 'available', 'Fiction', 'Fantasy', 2020, 304, 0, NULL),
(438, 'Educated', 'Tara Westover', 'Educated.jpg', 'available', 'Non-Fiction', 'Memoir', 2018, 334, 0, NULL),
(439, 'The Testaments', 'Margaret Atwood', 'TheTestaments.jpg', 'available', 'Fiction', 'Dystopian', 2019, 419, 1, NULL),
(440, 'Where the Crawdads Sing', 'Delia Owens', 'WhereTheCrawdadsSing.jpg', 'available', 'Fiction', 'Mystery', 2018, 368, 0, NULL),
(441, 'A Gentleman in Moscow', 'Amor Towles', 'AGentlemanInMoscow.jpg', 'available', 'Fiction', 'Historical', 2016, 462, 0, NULL),
(442, 'The Seven Husbands of Evelyn Hugo', 'Taylor Jenkins Reid', 'TheSevenHusbandsOfEvelynHugo.jpg', 'available', 'Fiction', 'Romance', 2017, 388, 0, NULL),
(443, 'It Ends with Us', 'Colleen Hoover', 'ItEndsWithUs.jpg', 'available', 'Fiction', 'Romance', 2016, 384, 0, NULL),
(444, 'The Outsiders', 'S.E. Hinton', 'TheOutsiders.jpg', 'available', 'Fiction', 'Young Adult', 1967, 192, 0, NULL),
(445, 'Gone Girl', 'Gillian Flynn', 'GoneGirl.jpg', 'available', 'Fiction', 'Thriller', 2012, 432, 14, NULL),
(446, 'The Rosie Project', 'Graeme Simsion', 'TheRosieProject.jpg', 'available', 'Fiction', 'Romance', 2013, 295, 0, NULL),
(447, 'The Goldfinch', 'Donna Tartt', 'TheGoldfinch.jpg', 'available', 'Fiction', 'Literary', 2013, 784, 0, NULL),
(448, 'The Night Circus', 'Erin Morgenstern', 'TheNightCircus.jpg', 'available', 'Fiction', 'Fantasy', 2011, 387, 1, NULL),
(449, 'Dune', 'Frank Herbert', 'Dune.jpg', 'available', 'Fiction', 'Science Fiction', 1965, 412, 0, NULL),
(450, 'The Kite Runner', 'Khaled Hosseini', 'TheKiteRunner.jpg', 'available', 'Fiction', 'Historical', 2003, 371, 1, NULL),
(451, 'The Road', 'Cormac McCarthy', 'TheRoad.jpg', 'available', 'Fiction', 'Post-Apocalyptic', 2006, 287, 0, NULL),
(452, 'Nell & Her GrandFather', 'Charles Dickens', 'Nell & Her GrandFather.jpg', 'UnAvailable', 'Fiction', 'Family', 1997, NULL, 12, NULL),
(453, 'Frozen Frontiers', 'Ox-Mammoth Animation', 'Frozen-Frontiers.jpg', 'UnAvailable', 'Fiction', 'Action, Apocalypse, Survival', 2024, NULL, 113, 'qwertyuiop'),
(454, 'Myonir', 'delux', '1c77f4e6c3f2d1a4e354a8247e345518.jpg', 'UnAvailable', 'Fiction', 'cartoon network', 1209, NULL, 0, 'Cloud Computing Problem Definition.txt');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `ChapterID` int(11) NOT NULL,
  `Chapter_title` varchar(100) NOT NULL,
  `File_path` varchar(255) NOT NULL,
  `book_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`ChapterID`, `Chapter_title`, `File_path`, `book_id`, `created_at`, `updated_at`) VALUES
(1, 'Chapter_1', 'rye1.txt', 419, '2024-11-02 07:51:05', '2024-11-02 07:51:05'),
(2, 'Chapter_II', 'rye2.txt', 419, '2024-10-26 02:02:28', '2024-11-02 07:17:22'),
(3, 'Chapter 1', 'w&pchapterI.txt', 425, '2024-11-07 09:14:22', '2024-11-07 09:14:22'),
(4, 'Epilogue 0', 'Definitions.txt', 445, '2024-11-09 07:41:45', '2024-11-09 07:41:45'),
(5, 'Beginning', 'Chapter01.pdf', 414, '2024-11-09 07:42:54', '2024-11-09 07:42:54'),
(6, 'Intro', 'etivity-1_inclass.pdf', 453, '2024-11-09 08:21:46', '2024-11-09 08:21:46');

-- --------------------------------------------------------

--
-- Table structure for table `favorite_books`
--

CREATE TABLE `favorite_books` (
  `book_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite_books`
--

INSERT INTO `favorite_books` (`book_id`, `username`, `added_on`) VALUES
(412, 'msp', '2024-11-08 14:36:03'),
(419, 'msp', '2024-11-07 08:45:41'),
(425, 'msp', '2024-11-08 00:05:56');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `book_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `review` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`book_id`, `username`, `review`, `created_at`) VALUES
(411, 'A_god', 'it\'s a master piece', '2024-11-07 05:43:37'),
(411, 'A_god', 'just another day in the office', '2024-11-07 05:44:10'),
(411, 'A_god', 'just another day in the office', '2024-11-07 05:50:21'),
(411, 'A_god', 'Heal it or break it apart', '2024-11-07 05:57:28'),
(420, 'Sam', 'hurry up and upload the chapters for this book already', '2024-11-07 08:05:54'),
(453, 'A_god', 'Sike', '2024-11-09 08:36:28'),
(453, 'A_god', 'best story ever', '2024-11-09 08:36:51'),
(419, 'A_god', 'dope', '2024-11-09 15:29:05'),
(419, 'A_god', 'lammmeee', '2024-11-09 15:31:21'),
(419, 'A_god', 'lammmeeeh', '2024-11-09 15:34:53');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `FIRSTNAME` varchar(100) NOT NULL,
  `LASTNAME` varchar(100) NOT NULL,
  `STAFFID` varchar(100) NOT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `DEPARTMENT` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`FIRSTNAME`, `LASTNAME`, `STAFFID`, `EMAIL`, `DEPARTMENT`) VALUES
('IMPRISONED', 'MATYR', 'CMYMCM', 'imprisoned_matyr@hotmail.com', 'Librarian');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `staffid` varchar(50) DEFAULT NULL,
  `is_staff` tinyint(1) NOT NULL DEFAULT 0,
  `is_banned` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`firstname`, `lastname`, `username`, `email`, `password`, `staffid`, `is_staff`, `is_banned`) VALUES
('Imprisoned', 'matyr', 'A_god', 'imprisoned_matyr@hotmail.com', '$2y$10$BlrrJ82z3oyD6WRC05BYKuX.vYHwNDQDWk0q5HBWt05TTjMug0PSe', 'CMYMCM', 1, 0),
('mandre', 'sqmson pol ', 'msp', 'samsonshay@outlook.com', '$2y$10$udv7OYtZBKOgpwLxuwHubumbI1SKQV0C99DmpgK1WRkdrFjeQ2M1G', NULL, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`ChapterID`),
  ADD KEY `fk_id` (`book_id`);

--
-- Indexes for table `favorite_books`
--
ALTER TABLE `favorite_books`
  ADD UNIQUE KEY `book_id` (`book_id`,`username`),
  ADD KEY `fk` (`username`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD KEY `fk_rev` (`book_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`STAFFID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_staffid` (`staffid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=455;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `ChapterID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `fk_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON UPDATE CASCADE;

--
-- Constraints for table `favorite_books`
--
ALTER TABLE `favorite_books`
  ADD CONSTRAINT `favorite_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_rev` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_staffid` FOREIGN KEY (`staffid`) REFERENCES `staff` (`STAFFID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

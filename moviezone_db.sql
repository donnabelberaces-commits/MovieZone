-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2026 at 11:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moviezone_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$aQQmeZFdNrKjicbJsY8P5ehaTMX6mRk13oaFYZgFevMiX8C97qGdq', '2026-10-09 20:14:15');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `release_year` int(11) NOT NULL,
  `rating` decimal(3,1) DEFAULT 0.0,
  `duration` int(11) NOT NULL,
  `description` text NOT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `synopsis` text DEFAULT NULL,
  `cast_members` text DEFAULT NULL,
  `director` varchar(150) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `trailer_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `genre`, `release_year`, `rating`, `duration`, `description`, `poster`, `created_at`, `synopsis`, `cast_members`, `director`, `release_date`, `language`, `country`, `trailer_url`) VALUES
(1, 'The Maze Runner', 'Action, Sci-Fi, Thriller', 2014, 10.0, 113, 'Thomas wakes up in a mysterious maze with a group of boys. Together, they must find a way out and uncover the truth.', 'images/maze-runner.jpg', '2026-10-09 19:59:21', 'Thomas wakes up in a mysterious place called the Glade, surrounded by a group of boys who have no clear memories of their past. Beyond their home lies a dangerous maze filled with deadly creatures. As Thomas tries to uncover the truth, he and the others must work together to find a way out and discover why they were brought there.', 'Dylan OBrien, Kaya Scodelario, Thomas Brodie-Sangster, Will Poulter, Ki Hong Lee', 'Wes Ball', '2014-09-19', 'English', 'United States', NULL),
(2, 'Divergent', 'Action, Sci-Fi, Adventure', 2014, 9.5, 139, 'Tris discovers that she is Divergent in a society divided into factions, putting herself in danger as she uncovers a conspiracy.', 'images/divergent.jpg', '2026-10-09 19:59:21', 'In a future version of Chicago, society is divided into factions based on different human virtues. Tris Prior discovers that she is Divergent, meaning she does not fit into just one faction. While training with Dauntless, she faces dangerous challenges and uncovers a conspiracy that threatens the society she knows.', 'Shailene Woodley, Theo James, Ashley Judd, Jai Courtney, Ansel Elgort', 'Neil Burger', '2014-03-21', 'English', 'United States', NULL),
(3, 'Snowpiercer', 'Action, Sci-Fi, Thriller', 2013, 8.5, 126, 'In a frozen world, survivors live on a moving train. A rebellion begins as passengers fight against an unfair social system.', 'images/snowpiercer.jpg', '2026-10-09 19:59:21', 'After a failed attempt to stop global warming leaves Earth frozen, the remaining survivors live aboard a train that circles the planet. The passengers are separated by social class, with the poorest living in harsh conditions at the back. Curtis leads a rebellion toward the front of the train, where he hopes to change the system and discover its secrets.', 'Chris Evans, Song Kang-ho, Tilda Swinton, Jamie Bell, Octavia Spencer, Go Ah-sung', 'Bong Joon Ho', '2013-08-01', 'English, Korean, French, Japanese, Czech', 'South Korea, Czech Republic', NULL),
(4, 'A Walk to Remember', 'Romance, Drama', 2002, 10.0, 101, 'A popular high school student falls in love with a quiet girl, and their relationship teaches him about love and personal growth.', 'images/a-walk-to-remember.jpg', '2026-10-09 19:59:21', 'Landon Carter is a popular but rebellious high school student who is required to participate in a school play. There, he grows closer to Jamie Sullivan, a quiet and kind girl whom he once barely noticed. As their friendship turns into love, Jamie helps Landon become a better person, while a difficult truth changes the course of their relationship.', 'Mandy Moore, Shane West, Peter Coyote, Daryl Hannah, Lauren German', 'Adam Shankman', '2002-01-25', 'English', 'United States', NULL),
(5, 'A Moment to Remember', 'Romance, Drama', 2004, 9.8, 117, 'A Korean romantic drama about a couple whose love is tested when the woman begins to lose her memories.', 'images/a-moment-to-remember.jpg', '2026-10-09 19:59:21', 'Su-jin and Cheol-su fall deeply in love and build a life together. Their relationship faces a heartbreaking challenge when Su-jin develops early-onset Alzheimers disease, causing her memories to fade. As her condition progresses, Cheol-su struggles to preserve their bond and care for the woman he loves.', 'Son Ye-jin, Jung Woo-sung, Baek Jong-hak, Lee Sun-jin', 'John H. Lee', '2004-11-05', 'Korean', 'South Korea', NULL),
(6, 'Resident Evil', 'Action, Horror, Science Fiction, Zombie Horror', 2002, 9.8, 100, '', 'images/e0021b680610b32e4324a361f5ad774d.jpg', '2026-10-11 01:09:13', 'The story follows Alice, who wakes up with no memory inside a secret underground facility called the Hive. She joins an elite military team to investigate a deadly virus outbreak caused by the Umbrella Corporation. The virus turns people into flesh-eating zombies, while dangerous mutant creatures roam the facility. Alice and the team must survive the horrors inside and stop the virus from spreading to the outside world.', 'Milla Jovovich, Michelle Rodriguez, Eric Mabius, James Purefoy, Martin Crewes and Colin Salmon', 'Paul W. S. Anderson', NULL, NULL, NULL, NULL),
(7, 'Way Back Home', 'Drama, Family', 2011, 10.0, 105, '', 'images/b9066dd72da27f45bf3c3a3d533fc3b4.jpg', '2026-10-11 01:15:35', 'Way Back Home is a Filipino family drama about two sisters, Jessica and Joanna, who were separated when they were young. After twelve years, Joanna, who grew up as Ana in a simple fishing family, is reunited with her biological family. However, Jessica feels jealous and neglected because her parents begin giving Ana more attention. Their growing rivalry creates conflict within the family, testing their love, trust, and sisterhood. The film shows the importance of family, forgiveness, and understanding.', 'Kathryn Bernardo, Julia Montes, Sam Concepcion, Enrique Gil, Agot Isidro, Tonton Gutierrez and Lotlot de Leon', 'Jerry Lopez Sineneng', NULL, NULL, NULL, NULL),
(8, 'Zootopia', 'Animation, Adventure, Comedy, Crime', 2016, 8.5, 108, '', 'images/696035412a10c513b958adeb15db124d.jpg', '2026-10-11 01:30:17', 'Zootopia follows Judy Hopps, a small rabbit who dreams of becoming a police officer in the big city of Zootopia. Despite facing discrimination because she is a rabbit, Judy works hard to prove herself. When a mysterious case involving missing animals arises, she teams up with Nick Wilde, a clever fox and former scam artist. Together, they uncover a bigger conspiracy that threatens the city. Along the way, they learn about trust, teamwork, and the importance of looking beyond stereotypes.', 'Ginnifer Goodwin, Jason Bateman, Idris Elba, Jenny Slate, Shakira, J.K. Simmons and Nate Torrence', 'Byron Howard and Rich Moore', NULL, NULL, NULL, NULL),
(9, 'DOA (Dead or Alive)', 'Action, Adventure, Martial Arts, Science Fiction', 2006, 10.0, 87, '', 'images/18e0d0deab95f8fc933cbf4ec4ff99d8.jpg', '2026-10-11 01:36:20', 'DOA: Dead or Alive follows a group of skilled fighters invited to compete in a secret martial arts tournament on a remote island. Among them are Kasumi, a princess and trained ninja; Tina, a professional wrestler; Christie, a thief and assassin; and Helena, the daughter of the tournament\'s former organizer. Although the women begin as rivals, they eventually work together after discovering a dangerous secret behind the competition. They must use their fighting skills and teamwork to defeat the threat and uncover the truth.', 'Devon Aoki, Jaime Pressly, Holly Valance, Sarah Carter, Natassia Malthe, Eric Roberts and Kane Kosugi', 'Corey Yuen', NULL, NULL, NULL, NULL),
(10, 'Me Before You', 'Romance, Drama', 2016, 9.5, 110, '', 'images/8935a48a4de85c3a493c86b493a188eb.jpg', '2026-10-11 01:42:17', 'Me Before You tells the story of Louisa Clark, a cheerful young woman who becomes a caregiver for Will Traynor, a wealthy man who becomes paralyzed after an accident. At first, Will feels hopeless about his life, but Lou\'s kindness and positive attitude gradually bring happiness into his world. As they spend more time together, they develop a deep emotional connection and fall in love. Lou tries to help Will rediscover the beauty of life, while both learn important lessons about love, courage, and making life meaningful.', 'Emilia Clarke, Sam Claflin, Janet McTeer, Charles Dance, Jenna Coleman and Matthew Lewis', 'Thea Sharrock', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

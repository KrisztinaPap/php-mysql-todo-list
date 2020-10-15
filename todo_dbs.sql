--
-- Database: `todo_dbs`
--

-- Drop tables if they exist
DROP TABLE IF EXISTS Task;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Active;

-- Create a new Active Table
CREATE TABLE IF NOT EXISTS Active (
    ActiveID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ActiveDescription varchar(20) NOT NULL
);

-- Create a new Category Table
CREATE TABLE IF NOT EXISTS Category (
    CategoryID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CategoryDescription varchar(20) NOT NULL
);

-- Create a new Task Table
CREATE TABLE IF NOT EXISTS Task (
    TaskID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CategoryID int(11) NOT NULL,
    ActiveID int(11) NOT NULL,
    TaskName varchar(30) NOT NULL,
    DueDate date NOT NULL,
    CompletedDate date NULL,
    CONSTRAINT FK_Task_Category FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID),
    CONSTRAINT FK_Task_Active FOREIGN KEY (ActiveID) REFERENCES Active(ActiveID)
);

-- Insert Sample CategoryDescriptions
INSERT INTO Category (CategoryID, CategoryDescription)
VALUES
(NULL, 'Chores'),
(NULL, 'Homework');

-- Insert ActiveDescriptions
INSERT INTO Active (ActiveID, ActiveDescription)
VALUES
(1, 'Active'),
(2, 'Inactive');

INSERT INTO Task (TaskID, CategoryID, ActiveID, TaskName, DueDate, CompletedDate)
VALUES
(NULL, 2, 1, 'Math homework', '1900-01-01', NULL),
(NULL, 2, 1, 'Write code', '2010-04-09', NULL),
(NULL, 1, 1, 'Exercise', '2023-03-03', NULL),
(NULL, 1, 1, 'Read to the kids', '2020-10-17', NULL),
(NULL, 2, 1, 'Take out trash', '2020-10-17', NULL),
(NULL, 1, 1, 'Feed the cat', '2020-10-17', NULL),
(NULL, 1, 2, 'Do dishes', '1683-07-04', '2020-03-23'); 
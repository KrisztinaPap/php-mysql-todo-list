--
-- Database: `todo_dbs`
--

-- Drop tables if they exist
DROP TABLE IF EXISTS Task;
DROP TABLE IF EXISTS Status;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Active;

-- Create a new Active Table
CREATE TABLE IF NOT EXISTS Active (
    ActiveID int(11) PRIMARY KEY AUTO_INCREMENT,
    ActiveDescription varchar(20) NOT NULL
);

-- Create a new Category Table
CREATE TABLE IF NOT EXISTS Category (
    CategoryID int(11) PRIMARY KEY AUTO_INCREMENT,
    CategoryDescription varchar(20) NOT NULL
);

-- Create a new Status Table
CREATE TABLE IF NOT EXISTS Status (
    StatusID int(11) PRIMARY KEY AUTO_INCREMENT,
    StatusDescription varchar(20) NOT NULL
);

-- Create a new Task Table
CREATE TABLE IF NOT EXISTS Task (
    TaskID int(11) PRIMARY KEY AUTO_INCREMENT,
    StatusID int(11) NOT NULL,
    CategoryID int(11) NOT NULL,
    ActiveID int(11) NOT NULL,
    TaskName varchar(30) NOT NULL,
    DueDate date NULL,
    CompletedDate date NULL,
    CONSTRAINT FK_Task_Status FOREIGN KEY (StatusID) REFERENCES Status(StatusID),
    CONSTRAINT FK_Task_Category FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID),
    CONSTRAINT FK_Task_Active FOREIGN KEY (ActiveID) REFERENCES Active(ActiveID)
);

-- Insert StatusDescriptions
INSERT INTO Status (StatusID, StatusDescription)
VALUES
(NULL, 'To do'),
(NULL, 'Overdue'),
(NULL, 'Completed');

-- Insert Sample CategoryDescriptions
INSERT INTO Category (CategoryID, CategoryDescription)
VALUES
(NULL, 'Chores'),
(NULL, 'Homework');

-- Insert ActiveDescriptions
INSERT INTO Active (ActiveID, ActiveDescription)
VALUES
(NULL, 'Active'),
(NULL, 'Inactive');


-- SELECT TaskName, DueDate, StatusDescription, CategoryDescription FROM Task INNER JOIN Status USING(StatusID) INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 1; 

/* INSERT INTO Task (TaskID, StatusID, CategoryID, ActiveID, TaskName, DueDate, CompletedDate)
VALUES
(NULL, 1, 2, 1, 'Math homework', NULL, NULL),
(NULL, 1, 2, 1, 'Write code', NULL, NULL),
(NULL, 1, 1, 1, 'Exercise', NULL, NULL),
(NULL, 1, 1, 1, 'Read to the kids', NULL, NULL),
(NULL, 1, 1, 2, 'Do dishes', NULL, NULL); */
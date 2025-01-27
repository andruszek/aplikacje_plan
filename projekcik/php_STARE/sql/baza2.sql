CREATE TABLE Teacher (
                        Teacher_ID INTEGER PRIMARY KEY AUTOINCREMENT,
                        FirstName VARCHAR(50),
                        LastName VARCHAR(50)
);

CREATE TABLE Subject (
                        Subject_ID INTEGER PRIMARY KEY AUTOINCREMENT,
                        Subject_Name VARCHAR(100),
                        Subject_Type VARCHAR(100)
);

CREATE TABLE Building (
                        Building_ID INTEGER PRIMARY KEY AUTOINCREMENT,
                        Building_Name VARCHAR(100)
);

CREATE TABLE Department (
                        Department_ID INTEGER PRIMARY KEY AUTOINCREMENT,
                        Department_Name VARCHAR(100)
);

CREATE TABLE Student (
                        Album_Number INTEGER PRIMARY KEY
);

CREATE TABLE Groups (
                        Group_ID INTEGER PRIMARY KEY AUTOINCREMENT,
                        Group_Name VARCHAR(50)
);

CREATE TABLE Room
(
                        Room_ID INTEGER PRIMARY KEY AUTOINCREMENT,
                        Room_Name VARCHAR(50)
);

CREATE TABLE Group_Student (
                        Group_ID INTEGER,
                        Album_Number INTEGER,
                        PRIMARY KEY (Group_ID, Album_Number),
                        FOREIGN KEY (Group_ID) REFERENCES Groups (Group_ID),
                        FOREIGN KEY (Album_Number) REFERENCES Student(Album_Number)
);

CREATE TABLE Classes (
                        CLasses_ID INTEGER PRIMARY KEY AUTOINCREMENT,
                        Subject_ID INTEGER,
                        Room_Name VARCHAR(50),
                        Start TIME,
                        End TIME,
                        Teacher_ID INTEGER,
                        Building_ID INTEGER,
                        Group_ID INTEGER,
                        Department_ID INTEGER,
                        Date DATE,
                        Status TEXT,
                        FOREIGN KEY (Subject_ID) REFERENCES Subject(Subject_ID),
                        FOREIGN KEY (Teacher_ID) REFERENCES Teacher(Teacher_ID),
                        FOREIGN KEY (Building_ID) REFERENCES Building(Building_ID),
                        FOREIGN KEY (Group_ID) REFERENCES Groups (Group_ID),
                        FOREIGN KEY (Department_ID) REFERENCES Department(Department_ID)
);

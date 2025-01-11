
CREATE TABLE Wykladowcy (
    ID_Wykladowcy INT AUTO_INCREMENT PRIMARY KEY,
    Imie VARCHAR(50),
    Nazwisko VARCHAR(50),
    Tytul_naukowy VARCHAR(70)
);

CREATE TABLE Przedmioty (
    ID_Przedmiotu INT AUTO_INCREMENT PRIMARY KEY,
    Nazwa_Przedmiotu VARCHAR(100),
    Typ_Zajec VARCHAR(100)
);

CREATE TABLE Budynek (
    ID_Budynku INT AUTO_INCREMENT PRIMARY KEY,
    Adres VARCHAR(100),
    Nazwa_Budynku VARCHAR(100)
);

CREATE TABLE Wydzial (
    ID_Wydzialu INT AUTO_INCREMENT PRIMARY KEY,
    Nazwa_Wydzialu VARCHAR(100)
);

CREATE TABLE Student (
    Numer_Albumu INT PRIMARY KEY
);

CREATE TABLE Grupa (
    ID_Grupy INT AUTO_INCREMENT PRIMARY KEY,
    Nazwa_Grupy VARCHAR(50)
);

CREATE TABLE Grupa_Student (
    ID_Grupy INT,
    Numer_Albumu INT,
    PRIMARY KEY (ID_Grupy, Numer_Albumu),
    FOREIGN KEY (ID_Grupy) REFERENCES Grupa(ID_Grupy),
    FOREIGN KEY (Numer_Albumu) REFERENCES Student(Numer_Albumu)
);

CREATE TABLE Zajecia (
    ID_Zajec INT AUTO_INCREMENT PRIMARY KEY,
    ID_Przedmiotu INT,
    Sala VARCHAR(50),
    Godzina_Startu TIME,
    Godzina_Konca TIME,
    ID_Wykladowcy INT,
    ID_Budynku INT,
    ID_Grupy INT,
    ID_Wydzialu INT,
    Data DATE,
    FOREIGN KEY (ID_Przedmiotu) REFERENCES Przedmioty(ID_Przedmiotu),
    FOREIGN KEY (ID_Wykladowcy) REFERENCES Wykladowcy(ID_Wykladowcy),
    FOREIGN KEY (ID_Budynku) REFERENCES Budynek(ID_Budynku),
    FOREIGN KEY (ID_Grupy) REFERENCES Grupa(ID_Grupy),
    FOREIGN KEY (ID_Wydzialu) REFERENCES Wydzial(ID_Wydzialu)
);



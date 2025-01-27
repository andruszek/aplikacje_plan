CREATE TABLE Teacher (
                            ID_Wykladowcy INTEGER PRIMARY KEY AUTOINCREMENT,
                            Imie VARCHAR(50),
                            Nazwisko VARCHAR(50)
);

CREATE TABLE Przedmioty (
                            ID_Przedmiotu INTEGER PRIMARY KEY AUTOINCREMENT,
                            Nazwa_Przedmiotu VARCHAR(100),
                            Typ_Zajec VARCHAR(100)
);

CREATE TABLE Budynek (
                         ID_Budynku INTEGER PRIMARY KEY AUTOINCREMENT,
                         Adres VARCHAR(100),
                         Nazwa_Budynku VARCHAR(100)
);

CREATE TABLE Wydzial (
                         ID_Wydzialu INTEGER PRIMARY KEY AUTOINCREMENT,
                         Nazwa_Wydzialu VARCHAR(100)
);

CREATE TABLE Student (
                         Numer_Albumu INTEGER PRIMARY KEY
);

CREATE TABLE Grupa (
                       ID_Grupy INTEGER PRIMARY KEY AUTOINCREMENT,
                       Nazwa_Grupy VARCHAR(50)
);

CREATE TABLE Sala
(
        ID_Sali    INTEGER PRIMARY KEY AUTOINCREMENT,
        Nazwa_Sali text
);
CREATE TABLE Grupa_Student (
                               ID_Grupy INTEGER,
                               Numer_Albumu INTEGER,
                               PRIMARY KEY (ID_Grupy, Numer_Albumu),
                               FOREIGN KEY (ID_Grupy) REFERENCES Grupa(ID_Grupy),
                               FOREIGN KEY (Numer_Albumu) REFERENCES Student(Numer_Albumu)
);

CREATE TABLE Zajecia (
                         ID_Zajec INTEGER PRIMARY KEY AUTOINCREMENT,
                         ID_Przedmiotu INTEGER,
                         Sala VARCHAR(50),
                         Godzina_Startu TIME,
                         Godzina_Konca TIME,
                         ID_Wykladowcy INTEGER,
                         ID_Budynku INTEGER,
                         ID_Grupy INTEGER,
                         ID_Wydzialu INTEGER,
                         Data DATE,
                         Status TEXT,
                         FOREIGN KEY (ID_Przedmiotu) REFERENCES Przedmioty(ID_Przedmiotu),
                         FOREIGN KEY (ID_Wykladowcy) REFERENCES Wykladowcy(ID_Wykladowcy),
                         FOREIGN KEY (ID_Budynku) REFERENCES Budynek(ID_Budynku),
                         FOREIGN KEY (ID_Grupy) REFERENCES Grupa(ID_Grupy),
                         FOREIGN KEY (ID_Wydzialu) REFERENCES Wydzial(ID_Wydzialu)
);

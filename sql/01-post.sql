create table post
(
    id      integer not null
        constraint post_pk
            primary key autoincrement,
    subject text not null,
    content text not null
);

create table games
(
    id      integer not null
        constraint games_pk
            primary key autoincrement,
    name text not null,
    rating text not null,
    info text not null
);
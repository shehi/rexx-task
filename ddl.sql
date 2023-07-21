SET @@session.foreign_key_checks = 0;

use rexx;

drop table if exists employees;

create table employees
(
    id    int unsigned auto_increment
        primary key,
    name  varchar(191) null,
    email varchar(191) null,
    constraint email unique (email)
);

drop table if exists events;

create table events
(
    id          int unsigned auto_increment primary key,
    name        varchar(191) not null,
    starts_at   datetime     not null,
    api_version varchar(191) not null
);

drop table if exists events_employees;

create table events_employees
(
    id          int unsigned auto_increment primary key,
    event_id    int unsigned null,
    employee_id int unsigned not null,
    fee         double(8, 2) not null,
    constraint events_employees_employee_id_foreign
        foreign key (employee_id) references employees (id)
            on update cascade on delete cascade,
    constraint events_employees_event_id_foreign
        foreign key (event_id) references events (id)
            on update cascade on delete cascade
);

SET @@session.foreign_key_checks = 1;



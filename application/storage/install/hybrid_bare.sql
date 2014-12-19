create database if not exists hybrid_bare;

create table hybrid_bare.hybrid_config
(
    `key` varchar(50) default null,
    `value` varchar(150) default null,
    `example` varchar(250) default null,
    `updated` INTEGER UNSIGNED default null
);

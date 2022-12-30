drop table if exists log;

create table log(
    id int AUTO_INCREMENT not null,
    operacion varchar(64) not null,
    descripcion text not null,
    created_by varchar(64),
    timestamp timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
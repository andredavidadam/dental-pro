drop table if exists tipologia;

create table tipologia(
    id int AUTO_INCREMENT not null,

    descrizione varchar(128),

    data_creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    created_by varchar(128),
    data_modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    modified_by varchar(128),
    PRIMARY KEY(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
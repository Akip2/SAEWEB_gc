Create Table utilisateur (
id INT(3) NOT NULL AUTO_INCREMENT,
nom varchar(20),
prenom varchar(20),
mail varchar(40),
motdepasse varchar(256),
role int(2),
Constraint primary key(id)
);

Create Table image(
id INT(3) NOT NULL AUTO_INCREMENT,
descImage varchar(500),
chemin varchar(60),
Constraint primary key(id)
);


Create Table touite(
id INT(4) NOT NULL AUTO_INCREMENT,
text varchar(235),
id_image int(3),
id_auteur int(3) NOT NULL,
datePubli datetime,
Constraint primary key(id),
foreign key(id_image) references image(id),
foreign key(id_auteur) references utilisateur(id)
);


Create Table tag(
id INT(3) NOT NULL AUTO_INCREMENT,
libelle varchar(30),
descTag varchar(50),
Constraint primary key(id)
);


Create Table touite2tag(
id_touite int(3) not null,
id_tag int(3) not null,
Constraint primary key(id_touite,id_tag),
foreign key(id_touite) references touite(id),
foreign key(id_tag) references tag(id)
);


Create Table suivreUtilisateur(
id_suiveur int(3) not null,
id_suivit int(3) not null,
Constraint primary key(id_suiveur,id_suivit),
foreign key(id_suiveur) references utilisateur(id),
foreign key(id_suivit) references utilisateur(id)
);


Create Table suivreTag(
id_suiveur int(3) not null,
id_tag int(3) not null,
Constraint primary key(id_suiveur,id_tag),
foreign key(id_suiveur) references utilisateur(id),
foreign key(id_tag) references tag(id)
);


Create Table evaluation(
id_touite int(3) not null,
id_utilisateur int(3) not null,
note int(1),
Constraint primary key(id_touite,id_utilisateur),
foreign key(id_touite) references touite(id),
foreign key(id_utilisateur) references utilisateur(id)
);

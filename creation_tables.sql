Create Table utilisateur (
id INT(3) NOT NULL AUTO_INCREMENT,
nom varchar(20), 
prenom varchar(20),
mail varchar(40),
motdepasse varchar(20),
role int(2),
Constraint primary key(id)
);

Create Table touite(
id INT(4) NOT NULL AUTO_INCREMENT ,
text varchar(235),
image varchar(60),
id_auteur int(3) NOT NULL,
note int(3),
datePubli date,
Constraint primary key(id),
foreign key(id_auteur) references utilisateur(id)
);

Create Table image(
id INT(3) NOT NULL AUTO_INCREMENT,
descImage varchar(500),
chemin varchar(60), 
Constraint primary key(id)
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

Create Table abonnement(
id_suiveur int(3) not null,
id_suivit int(3) not null,
Constraint primary key(id_suiveur,id_suivit),
foreign key(id_suiveur) references utilisateur(id),
foreign key(id_suivit) references utilisateur(id)
);

Create Table suivre(
id_suiveur int(3) not null,
id_tag int(3) not null,
Constraint primary key(id_suiveur,id_tag),
foreign key(id_suiveur) references utilisateur(id),
foreign key(id_tag) references tag(id)
);

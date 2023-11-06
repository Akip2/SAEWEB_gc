CREATE TABLE Article ( id_article INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
						titre VARCHAR(500),
						resume VARCHAR(500),
						typearticle VARCHAR(500));

CREATE TABLE Chercheur ( id_chercheur INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
							email VARCHAR(500),
							nomchercheur VARCHAR(500),
							prenomchercheur VARCHAR(500),
							urlchercheur VARCHAR(500));

CREATE TABLE Laboratoire ( id_labo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
							nomlabo VARCHAR(500),
							siglelabo VARCHAR(500),
							adresselabo VARCHAR(200),
							urllabo VARCHAR(500));

CREATE TABLE Support ( nomsupport VARCHAR(500) PRIMARY KEY NOT NULL,
						typesupport VARCHAR(500));

CREATE TABLE Ecrire ( id_chercheur INT,
						id_article INT,
						PRIMARY KEY (id_chercheur, id_article),
						FOREIGN KEY (id_chercheur) REFERENCES Chercheur (id_chercheur),
						FOREIGN KEY (id_article) REFERENCES Article (id_article));

CREATE TABLE Publier ( id_article INT,
						nomsupport VARCHAR(500),
						annee_publication INT,
						PRIMARY KEY (id_article, nomsupport),
						FOREIGN KEY (id_article) REFERENCES Article(id_article),
						FOREIGN KEY (nomsupport) REFERENCES Support(nomsupport));

CREATE TABLE Travailler ( id_chercheur INT,
							id_labo INT,
							PRIMARY KEY (id_chercheur, id_labo),
							FOREIGN KEY (id_chercheur) REFERENCES Chercheur(id_chercheur),
							FOREIGN KEY (id_labo) REFERENCES Laboratoire(id_labo));

CREATE TABLE Annoter ( id_annotation INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
						id_chercheur INT,
						id_article INT,
						libelle VARCHAR(500),
						FOREIGN KEY (id_chercheur) REFERENCES Chercheur(id_chercheur),
						FOREIGN KEY (id_article) REFERENCES Article(id_article));

CREATE TABLE Noter ( id_chercheur INT,
						id_article INT,
						note INT,
						PRIMARY KEY (id_chercheur, id_article),
						FOREIGN KEY (id_chercheur) REFERENCES Chercheur(id_chercheur),
						FOREIGN KEY (id_article) REFERENCES Article(id_article));
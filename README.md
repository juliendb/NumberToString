# NumberToString
Class PHP pour convertir un nombre en toute lettre avec monnaie en francais, par défault monnaie en euro.


Utilisation :

$myclass = new NumberToString();

$myclass->getString(5);
=> cinq euros

$myclass->getString(250.50);
=> deux cent cinquante euros et cinquante centimes

$myclass->getString(0.95);
=> quatre-vingt-quinze centimes d'euro

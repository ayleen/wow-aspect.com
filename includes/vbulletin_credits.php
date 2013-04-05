<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.0 Patch Level 3
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2000-2012 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/

if (!isset($GLOBALS['vbulletin']->db))
{
	exit;
}

// display the credits table for use in admin/mod control panels

print_form_header('index', 'home');
print_table_header($vbphrase['vbulletin_developers_and_contributors']);
print_column_style_code(array('white-space: nowrap', ''));
print_label_row('<b>' . $vbphrase['software_developed_by'] . '</b>', '
	vBulletin Solutions, Inc.,
	Internet Brands, Inc.
', '', 'top', NULL, false);
print_label_row('<b>' . $vbphrase['business_product_development'] . '</b>', '
	Alan Chiu,
	Gary Carroll,
	Lawrence Cole,
	Mark Jean,
	Neal Sainani,
	Omid Majdi
', '', 'top', NULL, false);
print_label_row('<b>' . $vbphrase['engineering'] . '</b>', '
	Alan Orduno,
	Brett Morriss,
	Danco Dimovski,
	David Grove,
	Edwin Brown,
	Fernando Varesi,
	Freddie Bingham,
	Glenn Vergara,
	Jay Quiambao,
	Jorge Tiznado,
	Kevin Sours,
	Kyle Furlong,
	Michael Lavaveshkul,
	Olga Mandrosov,
	Paul Marsden,
	Xiaoyu Huang,
	Zoltan Szalay
', '', 'top', NULL, false);
print_label_row('<b>' . $vbphrase['qa'] . '</b>', '
	Allen Lin,
	Fei Leung,
	Meghan Sensenbach,
	Michael Mendoza,
	Sebastiano Vassellatti,
	Yves Rigaud
', '', 'top', NULL, false);

print_label_row('<b>' . $vbphrase['support'] . '</b>', '
	Christine Tran,
	Danny Morlette,
	Dody,
	George Liu,
	Joe DiBiasi,
	Lynne Sands,
	Matthew Gordon,
	Michael Miller,
	Rene Jimenez,
	Riasat Al Jamil,
	Trevor Hannant,
	Wayne Luke,
	Yasser Hamde,
	Zachery Woods,
	Zuzanna Grande
', '', 'top', NULL, false);

print_label_row('<b>' . $vbphrase['special_thanks_and_contributions'] . '</b>', '
	Ace Shattock,
	Adrian Harris,
	Adrian Sacchi,
	Ahmed,
	Ajinkya Apte,
	Anders Pettersson,
	Andreas Kirbach,
	Andrew Elkins,
	Andy Huang,
	Aston Jay,
	Billy Golightly,
	bjornstrom,
	Bob Pankala,
	Brad Wright,
	Brian Swearingen,
	Brian Gunter,
	Carrie Anderson,
	Chen Avinadav,
	Chevy Revata,
	Chris Holland,
	Christian Hoffmann,
	Christopher Riley,
	Colin Frei,
	Daniel Clements,
	Darren Gordon,
	David Bonilla,
	David Webb,
	David Yancy,
	digitalpoint,
	Dominic Schlatter,
	Don Kuramura,
	Don T. Romrell,
	Doron Rosenberg,
	Elmer Hernandez,
	Emon Khan,
	Enrique Pascalin,
	Eric Johney,
	Eric Sizemore (SecondV),
	Fabian Schonholz,
	Fernando Munoz,
	Floris Fiedeldij Dop,
	Harry Scanlan,
	Gavin Robert Clarke,
	Geoff Carew,
	Giovanni Martinez,
	Green Cat,
	Hanafi Jamil,
	Hani Saad,
	Hanson Wong,
	Hartmut Voss,
	Ivan Anfimov,
	Ivan Milanez,
	Jacquii Cooke,
	Jake Bunce,
	Jan Allan Zischke,
	Jasper Aguila,
	Jaume L&oacute;pez,
	Jelle Van Loo,
	Jen Rundell,
	Jeremy Dentel,
	Jerry Hutchings,
	Joan Gauna,
	Joanna W.H.,
	Joe Rosenblum,
	Joe Velez,
	Joel Young,
	John Jakubowski,
	John McGanty,
	John Percival,
	John Yao,
	Jonathan Javier Coletta,
	Joseph DeTomaso,
	Justin Turner,
	Kay Alley,
	Kevin Connery,
	Kevin Schumacher,
	Kevin Wilkinson,
	Kier Darby,
	Kira Lerner,
	Kolby Bothe,
	Kym Farnik,
	Lamonda Steele,
	Lisa Swift,
	Marco Mamdouh Fahem,
	Mark Bowland,
	Mark Hennyey,
	Mark James,
	Marlena Machol,
	Martin Meredith,
	Maurice De Stefano,
	Merjawy,
	Mert Gokceimam,
	Michael Anders,
	Michael Biddle,
	Michael Fara,
	Michael Henretty,
	Michael Kellogg,
	Michael \'Mystics\' K&ouml;nig,
	Michael Pierce,
	Michlerish,
	Miguel Montaño,
	Mike Sullivan,
	Milad Kawas Cale,
	miner,
	Nathan Wingate,
	nickadeemus2002,
	Ole Vik,
	Oscar Ulloa,
	Overgrow,
	Peggy Lynn Gurney,
	Prince Shah,
	Pritesh Shah,
	Priyanka Porwal,
	Pieter Verhaeghe,
	Reenan Arbitrario,
	Refael Iliaguyev,
	Reshmi Rajesh,
	Ricki Kean,
	Rob (Boofo) Hindal,
	Robert Beavan White,
	Roms,
	Ruth Navaneetha,
	Ryan Ashbrook,
	Ryan Royal,
	Sal Colascione III,
	Scott MacVicar,
	Scott Molinari,
	Scott William,
	Scott Zachow,
	Shawn Vowell,
	Sophie Xie,
	Stefano Acerbetti,
	Stephan \'pogo\' Pogodalla,
	Steve Machol,
	Sven "cellarius" Keller,
	Tariq Bafageer,
	The Vegan Forum,
	ThorstenA,
	Tom Murphy,
	Tony Phoenix,
	Torstein H&oslash;nsi,
	Troy Roberts,
	Tully Rankin,
	Vinayak Gupta
	', '', 'top', NULL, false);
print_label_row('<b>' . $vbphrase['copyright_enforcement_by'] . '</b>', '
	vBulletin Solutions, Inc.
', '', 'top', NULL, false);
print_table_footer();

/*======================================================================*\
|| ####################################################################
|| # CVS: $RCSfile$ - $Revision: 62696 $
|| ####################################################################
\*======================================================================*/
?>

	  INTELLIGENIA MOBILE						 
	   __________________ 						 
	  /                  \ 						 
	 /    _____________   \						 
	/    /             \   \					 
	|   |              |   |					 
	|   |              |   |					 
	|   |              |   |					 
	|   |              |   |					 
	|   |              |   |					 
	|   |              |   |					 
	|    \_____   _____|__ |					 
	|\        /   \       \|					 
	| \_______\   /____    |					 
	|   |              \   |					 
	|   |               |  |					 
	\    \_____________/   /					 
     \                    /						 
	  \__________________/						 
												 


@author Rafael López
@team Intelligenia Mobile

Resource module de tipo Copicloud
=================================
Este módulo se ha creado de forma totalmente independiente  del núcleo de moodle, es decir, no es necesario
modificar moodle para poder acoplarlo.

Este módulo nos permite incorporar a moodle recursos de tipo Copicloud.

Un recurso de tipo Copicloud es similar a un tipo de recurso archivo. Lo que los
diferencia es el tipo de presentación y el modo de servir el recurso.

El recurso Copicloud se añade como cualquier otro recurso, con la particularidad, que ese archivo se
almacena en copicloud, y una vez almacenado desde moodle se nos proporciona un enlace para imprimir ese archivo
en Copicloud.

El cambio de los iconos se realiza mediante Javascript en el archivo lib.php de este recurso.

 * Cosas a tener en cuenta:
	+ En el archivo settings.php se encuentra los parámetros de configuración.
	+ Cuando se realicen cambios en el recurso debe cambiarse el numero de version en el version.php
	+ Si queremos añadir campos a la tabla copicloud debemos:
		- O bien alterar la base de datos directamente ( Teniendo en cuenta que habrá que modicar el código).
		- O bien alterar el archivo db/install.xml, y desinstar e instalar el plugin.

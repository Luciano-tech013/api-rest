***********************Documentacion de la API**************************
****Descripcion****
Api tipo REST para ofrecer servicios CRUD, con opcion de Filtrado. Acepta Recursos "Autos" y "Categorias"
Esta autenticada con token, de tipo JWT y algoritmo HS256, encriptada con Base64

****Endpoints****
URL: http://motorsportpage-rest/api/recurso
Metodo: GET
-Devuelve una coleccion de entidades del recurso especificado. No se requiere estar autenticado para este metodo
-Codigo de respuesta: 200 OK, 400 Bad Request

URL: http://motorsportpage-rest/api/recurso/id
Metodo: GET
-Si se le pasa parametro ID, devulve un item especifico del recurso especificado. No se requiere estar autenticado para este metodo. Devuelve el objeto del item
-Codigo de respuesta: 200 OK, 404 Not Found

URL: http://motorsportpage-rest/api/recurso
Metodo: POST
-Permite insertar un nuevo item a la tabla del recurso especificado. No devulve el ID del ultimo insertado, y tampoco se require pasarle ID. Se requiere estar autenticado para usar este método. Devuelve el objeto del nuevo item insertado
-Codigo de respuesta: 201 Created, 400 Bad Request

URL: http://motorsportpage-rest/api/recurso/id
Método: DELETE
-Permite borrar un item especifico a travéz de un ID de un recurso especificado. Devuelve el objeto del item eliminado
-Codigo de respuesta: 200 OK, 404 Not Found

URL: http://motorsportpage-rest/api/recurso/id
Método: PUT
-Permite modificar un item especifico a travéz de un ID. Devuelve el nuevo objeto modificado del item
-Codigo de respuesta: 201 Created, 404 Not Found

URL: http://motorsportpage-rest/api/auth/token
Método: GET
-Permite generar y traerse un Token para autenticar nuestra Api. Para los metodos POST, PUT Y DELETE se requiere solicitar un Token
-Codigo de respuesta: 200 OK, 401 Unauthorized

URL: http://motorsportpage-rest/api/recurso?sort=id&order=desc
Método: GET
-Si se le pasa los parametros sort y order con valores sort=id y order=desc permite ordenar todos los items del JSON mediante el ID de manera descendente. Si le pasas el parametro order=asc ordena de forma ascendente (como estaba en un principio). SOLAMENTE se puede ordenar por ID. No se requiere estar autenticado
Por seguridad, si el parametro order no guarda valores igual a 'asc' or 'desc' la api rechazara la solicitud (400 Bad request). Lo mismo sucede con el parametro Sort: Si la variable es diferente a algunos de los campos de la Base de Datos la API rechazara la solicitud (Ver linea 49 y 50)
-Codigo de respuesta: 200 OK, 400 Bad Request

URL: http://motorsportpage-rest/api/recurso?value=valor
Método: GET
-Si se le pasa el parametro value con valores de algun campo de la tabla, permite filtrar valores que usted desee. No se requiere estar autenticado
SOLO se puede filtrar por el campo Modelo (del recurso autos) y el campo Tipo (del recurso categorias). Como sucede con el ordenar, solo se puede filtrar valores especificos que se encuentren en ese campo, si no la API rechazara la solicitud (Ver linea 51 y 52)
-Codigo de respuesta: 200 OK, 400 Bad request

-Campos del recurso Autos: id, nombres, descripcion, modelo, marca, id_categorias
-Campos del recurso Categorias: id_categorias, nombre, descripcion, tipo
-Valores del campo Modelo del recurso Autos: GT3, GTE, LPM1, Hypercar, Turismo
-Valores del campo Tipo del recurso categorias: INTERNACIONAL, CONTINENTAL, ZONAL, PROVINCIAL, NACIONAL. (Ambos valores en mayuscula) 

/**Chequear si la documentacion esta bien*/
***********************Documentacion de la API**************************
****Descripcion****
Api tipo REST para ofrecer servicios CRUD, con opcion de Filtrado, Ordenamiento y Paginacion. Acepta Recursos "Autos" y "Categorias".
Esta autenticada con token, de tipo JWT y algoritmo HS256, encriptada con Base64.

****Endpoints****
URL: http://api-rest/api/recurso
Metodo: GET
-Devuelve una coleccion de entidades del recurso especificado. No se requiere estar autenticado para este metodo.
-Codigo de respuesta: 200 OK, 400 Bad Request

URL: http://api-rest/api/recurso/id
Metodo: GET
-Si se introduce un parametro ID, devulve un item especifico del recurso solicitado. No se requiere estar autenticado para este metodo. Devuelve el objeto del item.
-Codigo de respuesta: 200 OK, 404 Not Found

URL: http://api-rest/api/recurso
Metodo: POST
-Permite insertar un nuevo item a la tabla del recurso determinado. Devuelve el ID del ultimo item insertado sin necesidad de introducirle un ID. Se necesita estar autenticado para usar este método. 
-Codigo de respuesta: 201 Created, 400 Bad Request

URL: http://api-rest/api/recurso/id
Método: DELETE
-Permite borrar un item especifico a travéz de un ID del recurso solicitado. Devuelve el objeto del item eliminado.
-Codigo de respuesta: 200 OK, 404 Not Found

URL: http://api-rest/api/recurso/id
Método: PUT
-Permite modificar un item especifico a travéz de un ID. Devuelve el nuevo objeto modificado del item.
-Codigo de respuesta: 201 Created, 404 Not Found

URL: http://api-rest/api/auth/token
Método: GET
-Permite generar y traerse un Token para autenticar nuestra Api. Para los metodos POST Y PUT se requiere solicitar un Token.
-Codigo de respuesta: 200 OK, 401 Unauthorized

URL: http://api-rest/api/recurso?sort=id&order=desc
Método: GET
-Al introducir los parametros sort y order con valores sort=id y order=desc permite ordenar todos los items del JSON mediante el ID de manera descendente. Si el valor es igual a order=asc ordena de forma ascendente (como estaba en un principio). No se requiere estar autenticado.
Por seguridad, si el parametro order no guarda valores igual a 'asc' o 'desc' la api rechazara la solicitud (400 Bad request), al igual que con el parametro Sort: Si la variable es diferente a algunos de los campos de la Base de Datos tambien la API rechazara la solicitud (Ver linea 49 y 50).
-Codigo de respuesta: 200 OK, 400 Bad Request

URL: http://api-rest/api/recurso?value=valor
Método: GET
-Si introducimos el parametro value con valores de la columna, permite filtrar valores que usted desee. No se requiere estar autenticado
SOLO se puede filtrar por el campo Modelo (del recurso autos) y el campo Tipo (del recurso categorias). Como sucede con el ordenar, solo se puede filtrar valores especificos que se encuentren en ese campo, si no la API rechazara la solicitud (Ver linea 51 y 52).
-Codigo de respuesta: 200 OK, 400 Bad request

URL: http://api-rest/api/recurso?page=valor&limit=valor
Método: GET
-Al introducir el parametro page=INT y limit=INT, permite paginar los resultados con la cantidad de paginas y limite de items que usted necesite. No se requiere estar autenticado.
Los valores de ambos parametros deben ser enteros, si no la API rechazara la solicitud.
-Codigo de respuesta: 200 OK, 400 Bad Request

-Campos del recurso Autos: id, nombres, descripcion, modelo, marca, id_categorias, nombre (de categoria).
-Campos del recurso Categorias: id_categorias, nombre, descripcion, tipo.
-Valores del campo Modelo del recurso Autos: GT3, GTE, LPM1, Hypercar, Turismo.
-Valores del campo Tipo del recurso categorias: INTERNACIONAL, CONTINENTAL, ZONAL, PROVINCIAL, NACIONAL. (Ambos valores en mayuscula).

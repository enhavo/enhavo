<?php

http_response_code(404);
echo '{
  "error": {
    "code": 404,
    "message": "Not Found: invalid receiver"
  }
}';
exit();

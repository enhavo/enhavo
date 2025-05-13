<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

http_response_code(404);
echo '{
  "error": {
    "code": 404,
    "message": "Not Found: invalid receiver"
  }
}';
exit;

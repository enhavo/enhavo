<?php
/**
 * ClassAnalyzer.php
 *
 * @since 06/07/15
 * @author gseidel
 */

namespace Enhavo\Component\ClassAnalyzer;

class ClassAnalyzer
{
    /**
     * @var string|null
     */
    protected $code = null;

    /**
     * @var array|null
     */
    private $tokens = null;

    /**
     * @var string|null
     */
    private $file = null;

    /**
     * @param $code string
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param $file string
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return array|null
     * @throws NoSourceException
     */
    protected function getTokens()
    {
        if($this->tokens == null) {
            if(is_string($this->code)) {
                $this->tokens = token_get_all($this->code);
            } elseif(is_string($this->file)) {
                if(!file_exists($this->file)) {
                    throw new NoSourceException(sprintf('The file "%s" was set, but it does not exists', $this->file));
                }
                $this->code = file_get_contents($this->file);
            } else {
                throw new NoSourceException('No file or code was set via setCode or setFile. Please call them first before you analyze the class');
            }
        }
        return $this->tokens;
    }

    /**
     * @return string
     */
    public function getFullClassName()
    {
        return sprintf('%s\%s', $this->getNamespace(), $this->getClassName());
    }

    /**
     * @return string|null
     * @throws NoSourceException
     */
    public function getClassName()
    {
        $tokens = $this->getTokens();
        $sequenceStart = false;
        $lastToken = null;
        foreach($tokens as $token) {
            if(is_string($token)) {
                $token = array(null, $token);
            }

            if($sequenceStart) {
                return $token[1];
            }

            if(($token[0] == T_WHITESPACE) && $lastToken[0] == T_CLASS) {
                $sequenceStart = true;
            }

            $lastToken = $token;
        }
        return null;
    }

    /**
     * @return string|null
     * @throws NoSourceException
     */
    public function getNamespace()
    {
        $tokens = $this->getTokens();
        $sequenceStart = false;
        $lastToken = null;
        $namespace = array();
        foreach($tokens as $token) {
            if(is_string($token)) {
                $token = array(null, $token);
            }

            if($sequenceStart) {
                if($token[0] == T_WHITESPACE || $token[1] === ';') {
                    break;
                }
                $namespace[] = $token[1];
            }

            if($token[0] == T_WHITESPACE && $lastToken[0] == T_NAMESPACE) {
                $sequenceStart = true;
            }

            $lastToken = $token;
        }
        if(count($namespace)) {
            return implode('', $namespace);
        }
        return null;
    }

    public function getUses()
    {
        $tokens = $this->getTokens();
        $sequenceStart = false;
        $lastToken = null;
        $uses = array();
        $use = array();
        foreach($tokens as $token) {
            if(is_string($token)) {
                $token = array(null, $token);
            }

            if($sequenceStart) {
                if($token[0] == T_WHITESPACE || $token[1] === ';') {
                    $sequenceStart = false;
                    $uses[] = $use;
                    $use = array();
                    continue;
                }
                $use[] = $token[1];
            }

            if($token[0] == T_WHITESPACE && $lastToken[0] == T_USE) {
                $sequenceStart = true;
            }

            $lastToken = $token;
        }

        $return = array();
        foreach($uses as $use) {
            $return[$use[count($use) - 1]] = implode($use);
        }
        return $return;
    }

    public function getConstructor()
    {
        $className = $this->getClassName();
        $functions = $this->getFunctions();
        foreach($functions as $function) {
            if ($function == '__construct') {
                return $this->getFunctionParameters($function);
            }
            if ($function == $className) {
                return $this->getFunctionParameters($function);
            }
        }
        return null;
    }

    public function getFunctionParameters($name)
    {
        $tokens = $this->getTokens();
        $sequenceStart = false;
        $lastToken = null;
        $beforeLastToken = null;
        $nameBuffer = array();
        $parameterSequence = false;
        $parameter = array();
        $parameters = array();

        foreach($tokens as $token) {
            if(is_string($token)) {
                $token = array(null, $token);
            }

            if($parameterSequence) {
                if($token[0] === T_STRING || $token[0] === T_NS_SEPARATOR || $token[0] === T_VARIABLE) {
                    $nameBuffer[] = $token[1];
                }

                if($token[0] == T_WHITESPACE || $token[1] == ',' || $token[1] == ')') {
                    if(count($nameBuffer)) {
                        $parameter[] = $nameBuffer;
                        $nameBuffer = array();
                    }
                }

                if($token[1] == ',' || $token[1] == ')') {
                    if(count($parameter)) {
                        $parameters[] = $parameter;
                        $parameter = array();
                    }
                }
            }

            if($sequenceStart) {
                if($token[1] == '(') {
                    $parameterSequence = true;
                }
                if($token[1] == ')') {
                    break;
                }
            }

            if($token[0] === T_STRING && $token[1] === $name && $beforeLastToken[0] === T_FUNCTION) {
                $sequenceStart = true;
            }

            $beforeLastToken = $lastToken;
            $lastToken = $token;
        }

        $return = array();
        foreach($parameters as $parameter) {
            if(count($parameter) == 1) {
                $return[] = array(null, implode('', $parameter[0]));
            }
            if(count($parameter) == 2) {
                $return[] = array(implode('', $parameter[0]), implode('', $parameter[1]));
            }
        }
        return $return;
    }

    public function getFunctions()
    {
        $tokens = $this->getTokens();
        $functions = array();
        $lastToken = null;
        $beforeLastToken = null;

        foreach($tokens as $token) {
            if(is_string($token)) {
                $token = array(null, $token);
            }

            if($token[0] === T_STRING && $beforeLastToken[0] === T_FUNCTION) {
                $functions[] = $token[1];
            }

            $beforeLastToken = $lastToken;
            $lastToken = $token;
        }

        return $functions;
    }
}

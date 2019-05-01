<?php

namespace Maba\Bundle\TwigTemplateModificationBundle\Twig;

use Twig_Lexer as BaseLexer;
use Twig_Token as Token;

class Lexer extends BaseLexer
{
    /**
     * @var ExtendedToken|null
     */
    private $lastToken = null;

    protected function pushToken($type, $value = '')
    {
        // do not push empty text tokens
        if (Token::TEXT_TYPE === $type && '' === $value) {
            return;
        }

        $cursor = $this->cursor;

        $rawOpeningTag = '{%';

        // this is the opening twig tag as a string, e.g. "{%", "{%-", "{{" etc...
        // we can use this to determine if we're in a whitespace ignoring block (if it ends with "-")
        if (isset($this->positions[0][$this->position][0])) {
            $rawOpeningTag = $this->positions[0][$this->position][0];
        }

        if ($type === Token::BLOCK_START_TYPE || $type === Token::VAR_START_TYPE) {
            // remove the correct number of characters based on the opening block, e.g. "{%" needs 2 characters removed
            // but blocks like "{%-" need 3 characters removed otherwise the "{" remains in the template and breaks it
            $cursor -= strlen($rawOpeningTag);
        }

        if ($this->lastToken !== null) {
            $this->lastToken->setEndCursor($cursor);
        }

        $token = new ExtendedToken($type, $value, $this->lineno);
        $token->setStartCursor($cursor);
        if ($type === Token::EOF_TYPE) {
            $token->setEndCursor($cursor);
        }

        $this->lastToken = $token;

        $this->tokens[] = $token;
    }
}

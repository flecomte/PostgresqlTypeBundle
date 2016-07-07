<?php

namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ArithmeticExpression;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class ContainsFunction extends FunctionNode
{

    /** @var ArithmeticExpression */
    private $expr1;

    /** @var ArithmeticExpression */
    private $expr2;

    public function parse (Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expr1 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->expr2 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql (SqlWalker $sqlWalker)
    {
        return sprintf(
            '(%s @> %s)',
            $this->expr1->dispatch($sqlWalker),
            $this->expr2->dispatch($sqlWalker)
        );
    }
}
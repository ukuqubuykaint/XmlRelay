<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Slim\Tests\Error;

use Exception;
use ReflectionClass;
use RuntimeException;
use Slim\Error\Renderers\HtmlErrorRenderer;
use Slim\Error\Renderers\JsonErrorRenderer;
use Slim\Error\Renderers\PlainTextErrorRenderer;
use Slim\Error\Renderers\XmlErrorRenderer;
use Slim\Exception\HttpException;
use Slim\Tests\TestCase;
use stdClass;

use function htmlspecialchars;
use function json_decode;
use function json_encode;
use function simplexml_load_string;

use const ENT_QUOTES;
use const ENT_SUBSTITUTE;

class AbstractErrorRendererTest extends TestCase
{
    public function testHTMLErrorRendererDisplaysErrorDetails()
    {
        $exception = new RuntimeException('Oops..');
        $renderer = new HtmlErrorRenderer();
        $output = $renderer->__invoke($exception, true);

        $this->assertMatchesRegularExpression(
            '/.*The application could not run because of the following error:.*/',
            $output
        );
        $this->assertStringContainsString('Oops..', $output);
    }

    public function testHTMLErrorRendererNoErrorDetails()
    {
        $exception = new RuntimeException('Oops..');
        $renderer = new HtmlErrorRenderer();
        $output = $renderer->__invoke($exception, false);

        $this->assertMatchesRegularExpression(
            '/.*A website error has occurred. Sorry for the temporary inconvenience.*/',
            $output
        );
        $this->assertStringNotContainsString('Oops..', $output);
    }

    public function testHTMLErrorRendererRenderFragmentMethod()
    {
        $exception = new Exception('Oops..', 500);
        $renderer = new HtmlErrorRenderer();
        $reflectionRenderer = new ReflectionClass(HtmlErrorRenderer::class);

        $method = $reflectionRenderer->getMethod('renderExceptionFragment');
        $this->setAccessible($method);
        $output = $method->invoke($renderer, $exception);

        $this->assertMatchesRegularExpression('/.*Type:*/', $output);
        $this->assertMatchesRegularExpression('/.*Code:*/', $output);
        $this->assertMatchesRegularExpression('/.*Message*/', $output);
        $this->assertMatchesRegularExpression('/.*File*/', $output);
        $this->assertMatchesRegularExpression('/.*Line*/', $output);
    }

    public function testHTMLErrorRendererRenderHttpException()
    {
        $exceptionTitle = 'title';
        $exceptionDescription = 'description';

        $httpExceptionProphecy = $this->prophesize(HttpException::class);

        $httpExceptionProphecy
            ->getTitle()
            ->willReturn($exceptionTitle)
            ->shouldBeCalledOnce();

        $httpExceptionProphecy
            ->getDescription()
            ->willReturn($exceptionDescription)
            ->shouldBeCalledOnce();

        $renderer = new HtmlErrorRenderer();
        $output = $renderer->__invoke($httpExceptionProphecy->reveal(), false);

        $this->assertStringContainsString($exceptionTitle, $output, 'Should contain http exception title');
        $this->assertStringContainsString($exceptionDescription, $output, 'Should contain http exception description');
    }

    public function testHTMLErrorRendererEscapesHttpException()
    {
        $exceptionTitle = '<script>alert(1)</script>';
        $exceptionDescription = "<script>alert('xss')</script>";

        $httpExceptionProphecy = $this->prophesize(HttpException::class);

        $httpExceptionProphecy
            ->getTitle()
            ->willReturn($exceptionTitle)
            ->shouldBeCalledOnce();

        $httpExceptionProphecy
            ->getDescription()
            ->willReturn($exceptionDescription)
            ->shouldBeCalledOnce();

        $renderer = new HtmlErrorRenderer();
        $output = $renderer->__invoke($httpExceptionProphecy->reveal(), false);

        $this->assertStringNotContainsString($exceptionTitle, $output, 'Title must not be rendered unescaped');
        $this->assertStringContainsString(
            htmlspecialchars($exceptionTitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $output,
            'Title must be HTML-escaped'
        );

        $this->assertStringNotContainsString(
            $exceptionDescription,
            $output,
            'Description must not be rendered unescaped'
        );
        $this->assertStringContainsString(
            htmlspecialchars($exceptionDescription, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $output,
            'Description must be HTML-escaped'
        );
    }

    public function testJSONErrorRendererDisplaysErrorDetails()
    {
        $exception = new Exception('Oops..');
        $renderer = new JsonErrorRenderer();
        $reflectionRenderer = new ReflectionClass(JsonErrorRenderer::class);

        $method = $reflectionRenderer->getMethod('formatExceptionFragment');
        $this->setAccessible($method);

        $fragment = $method->invoke($renderer, $exception);
        $output = json_encode(json_decode($renderer->__invoke($exception, true)));
        $expectedString = json_encode(['message' => 'Slim Application Error', 'exception' => [$fragment]]);

        $this->assertSame($output, $expectedString);
    }

    public function testJSONErrorRendererDoesNotDisplayErrorDetails()
    {
        $exception = new Exception('Oops..');

        $renderer = new JsonErrorRenderer();
        $output = json_encode(json_decode($renderer->__invoke($exception, false)));

        $this->assertSame($output, json_encode(['message' => 'Slim Application Error']));
    }


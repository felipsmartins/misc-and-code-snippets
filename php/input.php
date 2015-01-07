<?php
/**
 * string input (string $prompt)
 *     If the prompt argument is present, it is written to standard output without
 *     a trailing newline. The function then reads a line from input, stripping a
 *     trailing newline/spaces, and returns that.
 *
 * Here is an example:
 * <?php
 * $user_input = input("Enter your name:\n");
 * print $user_input;
 * ?>
 *
 */


/**
 * Ler uma linha da entrada padrão, remove os espaços (início e fim) e a retorna.
 * Isso funciona como a função input(), em Python 3
 *
 * @author Kazumi  <felipsmartins@gmail.com>
 * @param  string $prompt A mensagem que será exibida ao usuário
 * @return string|null retorna null se é executada fora do contexto de um console
 */
function input($prompt = null) {
    if (PHP_SAPI != 'cli') {
        trigger_error("input() só pode ser executada no contexto de consoles",
            E_USER_ERROR);
    }
    $prompt ? print $prompt : '';
    # stdin
    $inputstream = fopen('php://stdin', 'r');
    $streamdata  = fgets($inputstream);
    fclose($inputstream);

    return trim($streamdata);
}

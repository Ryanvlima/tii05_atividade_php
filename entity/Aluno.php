<?php

class Aluno {
    private $matricula;
    private $nome;
    private $disciplinas;

    public function __construct($matricula, $nome) {
        $this->matricula = $matricula;
        $this->nome = $nome;
        $this->disciplinas = [];
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    // Método para obter o array de disciplinas
    public function getDisciplinas() {
        return $this->disciplinas;
    }

    // Método para definir o array de disciplinas
    public function setDisciplinas(array $disciplinas) {
        $this->disciplinas = $disciplinas;
    }

    // Método para adicionar uma disciplina individual
    public function addDisciplina($disciplina) {
        $this->disciplinas[] = $disciplina;
    }
}
?>
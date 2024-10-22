<?php
require_once 'BaseDAO.php';
require_once 'entity/Aluno.php';
require_once 'entity/Disciplina.php';
require_once 'config/Database.php';

class AlunoDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM Aluno WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Aluno($row['matricula'], $row['nome']);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM Aluno";
        $stmt = $this->db->query($sql);
        $alunos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $alunos[] = new Aluno($row['matricula'], $row['nome']);
        }
        return $alunos;
    }

    public function create($aluno)
    {
        $sql = "INSERT INTO Aluno (nome) VALUES (:nome)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $aluno->getNome());
        $stmt->execute();
    }

    public function update($aluno)
    {
        $sql = "UPDATE Aluno SET nome = :nome WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $aluno->getNome());
        $stmt->bindParam(':id', $aluno->getId());
        $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Aluno WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getAlunoWithDisciplinas($alunoID)
{
    $sql = "
        SELECT aluno.matricula, aluno.nome AS aluno_nome,
               disciplina.id AS disciplina_id, disciplina.nome AS disciplina_nome, disciplina.carga_horaria
        FROM aluno
        JOIN disciplina_aluno ON aluno.matricula = disciplina_aluno.aluno_id
        JOIN disciplina ON disciplina_aluno.disciplina_id = disciplina.id
        WHERE aluno.matricula = :alunoID
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':alunoID', $alunoID);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$result) return null;

    // Cria o objeto Aluno
    $aluno = new Aluno($result[0]['matricula'], $result[0]['aluno_nome']);
    $aluno->setDisciplinas([]);

    // Adiciona disciplinas ao aluno
    foreach ($result as $row) {
        if (isset($row['disciplina_id'], $row['disciplina_nome'])) {
            $disciplina = new Disciplina($row['disciplina_id'], $row['disciplina_nome'], $row['carga_horaria']);
            $aluno->addDisciplina($disciplina);
        }
    }

    return $aluno;
}

}
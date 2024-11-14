<?php 
require_once 'Musica.php';

class Outro extends Musica {
    private string $nomeTipo;

    /**
     * Get the value of nomeTipo
     */
    public function getNomeTipo(): string
    {
        return $this->nomeTipo;
    }

    /**
     * Set the value of nomeTipo
     */
    public function setNomeTipo(string $nomeTipo): self
    {
        $this->nomeTipo = $nomeTipo;

        return $this;
    }
}
?>
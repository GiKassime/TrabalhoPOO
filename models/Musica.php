<?php 
require_once 'IMusica.php';

class Musica implements IMusica {
    protected string $nome;
    protected int $duracao;
    protected string $artista;
    protected string  $album;
    protected bool $status;
    public function __construct($nome,$duracao,$artista,$album,$status) {
        $this->nome = $nome;
        $this->duracao = $duracao;
        $this->artista = $artista;
        $this->album = $album;
        $this->status = $status;
    }
    public function __toString()
    {
        return "Nome:". $this->nome." | Duracao: ".$this->duracao." | Artista: ".$this->artista." | Album: ".$this->album;
    }
    public function tocarMusica(){
        if ($this->status) {
            return "\nA música já está tocando!.......";
        }else{
            return "\nMúsica tocando!.......";
            $this->status = true;
        }
    }
    public function pausarMusica(){
        if (!$this->status) {
            return "\nA música já está pausada!.......";
        }else{
            $this->status = false;
            return "\nMúsica pausada!.......";
        }
    }

    /**
     * Get the value of nome
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of duracao
     */
    public function getDuracao(): int
    {
        return $this->duracao;
    }

    /**
     * Set the value of duracao
     */
    public function setDuracao(int $duracao): self
    {
        $this->duracao = $duracao;

        return $this;
    }

    /**
     * Get the value of artista
     */
    public function getArtista(): string
    {
        return $this->artista;
    }

    /**
     * Set the value of artista
     */
    public function setArtista(string $artista): self
    {
        $this->artista = $artista;

        return $this;
    }

    /**
     * Get the value of album
     */
    public function getAlbum(): string
    {
        return $this->album;
    }

    /**
     * Set the value of album
     */
    public function setAlbum(string $album): self
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
?>
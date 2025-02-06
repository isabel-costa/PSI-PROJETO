package com.example.ticketgomobileapp.modelos;

public class Evento {
    private int id;
    private String titulo, descricao, dataInicio, dataFim, localNome, categoriaNome, imagemUrl;

    public Evento(int id, String titulo, String descricao, String dataInicio, String dataFim, String localNome, String categoriaNome) {
        this.id = id;
        this.titulo = titulo;
        this.descricao = descricao;
        this.dataInicio = dataInicio;
        this.dataFim = dataFim;
        this.localNome = localNome;
        this.categoriaNome = categoriaNome;
        this.imagemUrl = imagemUrl;
    }

    public int getId() {
        return id;
    }

    public String getTitulo() {
        return titulo;
    }

    public String getDescricao() {
        return descricao;
    }

    public String getDataInicio() {
        return dataInicio;
    }

    public String getDataFim() {
        return dataFim;
    }

    public String getLocalNome() {
        return localNome;
    }

    public String getCategoriaNome() {
        return categoriaNome;
    }

    public String getImagemUrl() {return imagemUrl;}

    public void setImagemUrl(String imagemUrl) {
        this.imagemUrl = imagemUrl;
    }
}
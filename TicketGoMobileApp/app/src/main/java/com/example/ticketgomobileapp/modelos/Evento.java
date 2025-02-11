package com.example.ticketgomobileapp.modelos;

public class Evento {
    private int id;
    private String titulo;
    private String descricao;
    private String dataInicio;
    private String dataFim;
    private String localNome;
    private String categoriaNome;
    private String imagemUrl;

    public Evento(int id, String titulo, String descricao, String dataInicio, String dataFim, String localNome, String categoriaNome, String imagemUrl) {
        this.id = id;
        this.titulo = titulo;
        this.descricao = descricao;
        this.dataInicio = dataInicio;
        this.dataFim = dataFim;
        this.localNome = localNome;
        this.categoriaNome = categoriaNome;
        this.imagemUrl = imagemUrl;
    }

    // Getters
    public int getId() { return id; }
    public String getTitulo() { return titulo; }
    public String getDescricao() { return descricao; }
    public String getDataInicio() { return dataInicio; }
    public String getDataFim() { return dataFim; }
    public String getLocalNome() { return localNome; }
    public String getCategoriaNome() { return categoriaNome; }
    public String getImagemUrl() { return imagemUrl; }

    // Setter para imagemUrl (caso precise alterar depois)
    public void setImagemUrl(String imagemUrl) {
        this.imagemUrl = imagemUrl;
    }
}

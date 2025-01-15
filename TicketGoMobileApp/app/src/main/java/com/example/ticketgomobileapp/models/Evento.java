package com.example.ticketgomobileapp.models;

public class Evento {
    private int id;
    private String titulo;
    private String descricao;
    private String datainicio;
    private String datafim;
    private int localId;
    private int categoriaId;

    // Construtores, getters e setters
    public Evento() {}

    public Evento(int id, String titulo, String descricao, String datainicio, String datafim, int localId, int categoriaId) {
        this.id = id;
        this.titulo = titulo;
        this.descricao = descricao;
        this.datainicio = datainicio;
        this.datafim = datafim;
        this.localId = localId;
        this.categoriaId = categoriaId;
    }

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    public String getTitulo() { return titulo; }
    public void setTitulo(String titulo) { this.titulo = titulo; }
    public String getDescricao() { return descricao; }
    public void setDescricao(String descricao) { this.descricao = descricao; }
    public String getDatainicio() { return datainicio; }
    public void setDatainicio(String datainicio) { this.datainicio = datainicio; }
    public String getDatafim() { return datafim; }
    public void setDatafim(String datafim) { this.datafim = datafim; }
    public int getLocalId() { return localId; }
    public void setLocalId(int localId) { this.localId = localId; }
    public int getCategoriaId() { return categoriaId; }
    public void setCategoriaId(int categoriaId) { this.categoriaId = categoriaId; }
}

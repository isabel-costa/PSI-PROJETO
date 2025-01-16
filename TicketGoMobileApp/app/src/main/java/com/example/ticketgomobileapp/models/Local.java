package com.example.ticketgomobileapp.models;

public class Local {
    private int id;
    private String lugar; // Nome do local
    private String morada; // Endereço
    private String cidade; // Cidade
    private int capacidade; // Capacidade

    public Local(int id, String lugar, String morada, String cidade, int capacidade) {
        this.id = id;
        this.lugar = lugar;
        this.morada = morada;
        this.cidade = cidade;
        this.capacidade = capacidade;
    }

    // Getters e Setters
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    public String getLugar() { return lugar; }
    public void setLugar(String lugar) { this.lugar = lugar; }
    public String getMorada() { return morada; }
    public void setMorada(String morada) { this.morada = morada; }
    public String getCidade() { return cidade; }
    public void setCidade(String cidade) { this.cidade = cidade; }
    public int getCapacidade() { return capacidade; }
    public void setCapacidade(int capacidade) { this.capacidade = capacidade; }
}
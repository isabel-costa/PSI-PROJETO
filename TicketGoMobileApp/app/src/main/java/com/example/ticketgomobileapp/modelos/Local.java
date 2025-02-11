package com.example.ticketgomobileapp.modelos;

public class Local {
    private int id;
    private String nome;
    private String morada;
    private String cidade;
    private int capacidade;

    public Local(int id, String nome, String morada, String cidade, int capacidade) {
        this.id = id;
        this.nome = nome;
        this.morada = morada;
        this.cidade = cidade;
        this.capacidade = capacidade;
    }

    public int getId() {
        return id;
    }

    public String getNome() {
        return nome;
    }

    public String getMorada() {
        return morada;
    }

    public String getCidade() {
        return cidade;
    }

    public int getCapacidade() {
        return capacidade;
    }
}
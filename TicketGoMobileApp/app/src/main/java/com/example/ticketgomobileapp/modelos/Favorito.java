package com.example.ticketgomobileapp.modelos;

public class Favorito {
    private int id;
    private int profileId;
    private int eventoId;

    // Construtor completo (com ID)
    public Favorito(int id, int profileId, int eventoId) {
        this.id = id;
        this.profileId = profileId;
        this.eventoId = eventoId;
    }

    public Favorito() {
    }

    // Construtor alternativo (sem ID)
    public Favorito(int profileId, int eventoId) {
        this.profileId = profileId;
        this.eventoId = eventoId;
    }

    // Getters e Setters
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public int getProfileId() { return profileId; }
    public void setProfileId(int profileId) { this.profileId = profileId; }

    public int getEventoId() { return eventoId; }
    public void setEventoId(int eventoId) { this.eventoId = eventoId; }

    // Método útil para depuração
    @Override
    public String toString() {
        return "Favorito{" +
                "id=" + id +
                ", profileId=" + profileId +
                ", eventoId=" + eventoId +
                '}';
    }
}

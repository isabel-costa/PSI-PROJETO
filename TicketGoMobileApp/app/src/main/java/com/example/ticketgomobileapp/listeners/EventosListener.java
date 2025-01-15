package com.example.ticketgomobileapp.listeners;

import java.util.ArrayList;
import com.example.ticketgomobileapp.models.Evento;

public interface EventosListener {
    void onRefreshListaEventos(ArrayList<Evento> listaEventos);
}

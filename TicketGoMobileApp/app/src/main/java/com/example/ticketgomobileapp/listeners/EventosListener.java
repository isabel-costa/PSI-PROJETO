package com.example.ticketgomobileapp.listeners;

import java.util.ArrayList;
import java.util.List;

import com.example.ticketgomobileapp.models.Evento;
import com.example.ticketgomobileapp.models.Local;

public interface EventosListener {
    void onRefreshListaEventos(List<Evento> listaEventos, List<Local> listaLocais);

    void onError(String mensagemErro);

}

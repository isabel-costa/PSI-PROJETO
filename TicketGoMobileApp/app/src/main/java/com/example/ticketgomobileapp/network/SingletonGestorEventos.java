package com.example.ticketgomobileapp.network;

import android.content.Context;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;
import com.example.ticketgomobileapp.models.Evento;
import com.example.ticketgomobileapp.listeners.EventosListener;

import org.json.JSONObject;
import java.util.ArrayList;

public class SingletonGestorEventos {
    private static SingletonGestorEventos instance;
    private RequestQueue volleyQueue;
    private static final String URL_API_EVENTOS = "http://localhost/SIS-PROJETO/TicketGo/backend/web/api/eventos";
    private EventosListener eventosListener;

    private ArrayList<Evento> listaEventos;

    private SingletonGestorEventos(Context context) {
        volleyQueue = Volley.newRequestQueue(context.getApplicationContext());
        listaEventos = new ArrayList<>();
    }
    public void setEventosListener(EventosListener listener) {
        this.eventosListener = listener;
    }

    public static synchronized SingletonGestorEventos getInstance(Context context) {
        if (instance == null) {
            instance = new SingletonGestorEventos(context);
        }
        return instance;
    }

    public ArrayList<Evento> getListaEventos() {
        return listaEventos;
    }

    public void carregarEventosAPI(final Context context, final EventosListener listener) {
        JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, URL_API_EVENTOS, null,
                response -> {
                    listaEventos.clear();
                    for (int i = 0; i < response.length(); i++) {
                        try {
                            JSONObject eventoJson = response.getJSONObject(i);
                            Evento evento = new Evento(
                                    eventoJson.getInt("id"),
                                    eventoJson.getString("titulo"),
                                    eventoJson.getString("descricao"),
                                    eventoJson.getString("datainicio"),
                                    eventoJson.getString("datafim"),
                                    eventoJson.getInt("localId"),
                                    eventoJson.getInt("categoriaId")
                            );
                            listaEventos.add(evento);
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                    if (listener != null) {
                        listener.onRefreshListaEventos(listaEventos);
                    }
                },
                error -> {
                    error.printStackTrace();
                }
        );
        volleyQueue.add(request);
    }
}

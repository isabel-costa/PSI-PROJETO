package com.example.ticketgomobileapp.network;

import android.content.Context;
import android.util.Log;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;
import com.example.ticketgomobileapp.models.Evento;
import com.example.ticketgomobileapp.models.Local;
import com.example.ticketgomobileapp.listeners.EventosListener;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class SingletonGestorEventos {
    private static SingletonGestorEventos instance = null;
    private RequestQueue requestQueue;
    private static Context ctx;

    private SingletonGestorEventos(Context context) {
        ctx = context;
        requestQueue = getRequestQueue();
    }

    public static synchronized SingletonGestorEventos getInstance(Context context) {
        if (instance == null) {
            instance = new SingletonGestorEventos(context);
        }
        return instance;
    }

    public RequestQueue getRequestQueue() {
        if (requestQueue == null) {
            requestQueue = Volley.newRequestQueue(ctx.getApplicationContext());
        }
        return requestQueue;
    }

    public void carregarEventosELocaisAPI(final EventosListener listener) {
        String urlEventos = "http://10.0.2.2/SIS-PROJETO/TicketGo/backend/web/api/eventos";
        String urlLocais = "http://10.0.2.2/SIS-PROJETO/TicketGo/backend/web/api/locais";

        // Carregar locais
        JsonArrayRequest jsonArrayRequestLocais = new JsonArrayRequest(Request.Method.GET, urlLocais, null,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        List<Local> locais = new ArrayList<>();
                        try {
                            for (int i = 0; i < response.length(); i++) {
                                JSONObject localObj = response.getJSONObject(i);
                                int id = localObj.getInt("id");
                                String lugar = localObj.getString("lugar");
                                String morada = localObj.getString("morada");
                                String cidade = localObj.getString("cidade");
                                int capacidade = localObj.getInt("capacidade");

                                Local local = new Local(id, lugar, morada, cidade, capacidade);
                                locais.add(local);
                            }
                        } catch (JSONException e) {
                            Log.e("JSON Parsing", "Erro ao processar JSON: " + e.getMessage());
                            listener.onError("Erro ao processar locais: " + e.getMessage());
                            return; // Retorna se houver erro
                        }

                        // Carregar eventos após carregar locais
                        carregarEventosAPI(listener, locais);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e("API Error", "Erro na requisição: " + error.getMessage());
                        listener.onError("Erro na requisição: " + error.getMessage());
                    }
                }
        );

        getRequestQueue().add(jsonArrayRequestLocais);
    }

    private void carregarEventosAPI(final EventosListener listener, final List<Local> listaLocais) {
        String urlEventos = "http://10.0.2.2/SIS-PROJETO/TicketGo/backend/web/api/eventos";

        JsonArrayRequest jsonArrayRequestEventos = new JsonArrayRequest(Request.Method.GET, urlEventos, null,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        Log.d("API Response", "Response: " + response.toString());
                        ArrayList<Evento> eventos = new ArrayList<>();

                        try {
                            for (int i = 0; i < response.length(); i++) {
                                JSONObject eventoObj = response.getJSONObject(i);
                                int id = eventoObj.getInt("id");
                                String titulo = eventoObj.getString("titulo");
                                String descricao = eventoObj.getString("descricao");
                                String datainicio = eventoObj.getString("datainicio");
                                String datafim = eventoObj.getString("datafim");
                                int localId = eventoObj.getInt("local_id");
                                int categoriaId = eventoObj.getInt("categoria_id");

                                // Format the dates
                                datainicio = formatDate(datainicio);
                                datafim = formatDate(datafim);

                                Log.d("API Response", "Evento: " + titulo);
                                Evento evento = new Evento(id, titulo, descricao, datainicio, datafim, localId, categoriaId);
                                eventos.add(evento);
                            }
                            listener.onRefreshListaEventos(eventos, listaLocais);
                        } catch (JSONException e) {
                            Log.e("JSON Parsing", "Erro ao processar JSON: " + e.getMessage());
                            listener.onError("Erro ao processar eventos: " + e.getMessage());
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e("API Error", "Erro na requisição: " + error.getMessage());
                        listener.onError("Erro na requisição: " + error.getMessage());
                    }
                }
        );

        getRequestQueue().add(jsonArrayRequestEventos);
    }

    private String formatDate(String dateString) {
        try {
            // Original format from API
            SimpleDateFormat inputFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
            // Desired format for display
            SimpleDateFormat outputFormat = new SimpleDateFormat("dd/MM/yyyy HH:mm", Locale.getDefault());
            return outputFormat.format(inputFormat.parse(dateString));
        } catch (ParseException e) {
            Log.e("Date Parsing", "Erro ao processar datas: " + e.getMessage());
            return dateString; // Return the original string if parsing fails
        }
    }
}
package com.example.ticketgomobileapp.modelos;

import android.content.Context;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.Map;

public class Singleton {
    private static volatile Singleton instance = null;
    private static RequestQueue volleyQueue;
    private final String BaseIp = ""; // Substitua pelo IP correto

    private Singleton(Context context) {
        volleyQueue = Volley.newRequestQueue(context.getApplicationContext());
    }

    public static synchronized Singleton getInstance(Context context) {
        if (instance == null) {
            synchronized (Singleton.class) {
                if (instance == null) {
                    instance = new Singleton(context);
                }
            }
        }
        return instance;
    }

    private String BASE_URL(String ip) {
        return "http://" + ip + "/api/";
    }

    private boolean isNetworkAvailable(Context context) {
        ConnectivityManager connectivityManager = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();
    }

    // Função para ver eventos
    public void verEventos(Context context, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        if (!isNetworkAvailable(context)) {
            Toast.makeText(context, "Sem ligação à internet", Toast.LENGTH_SHORT).show();
        } else {
            SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
            String ip = prefs.getString("ip", BaseIp);
            JsonObjectRequest req = new JsonObjectRequest(Request.Method.GET, BASE_URL(ip) + "eventos", null, listener, errorListener);
            volleyQueue.add(req);
        }
    }

    // Função para ver detalhes de um evento
    public void verDetalhesEvento(Context context, int id, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        if (!isNetworkAvailable(context)) {
            Toast.makeText(context, "Sem ligação à internet", Toast.LENGTH_SHORT).show();
        } else {
            SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
            String ip = prefs.getString("ip", BaseIp);
            JsonObjectRequest req = new JsonObjectRequest(Request.Method.GET, BASE_URL(ip) + "eventos/" + id, null, new Response.Listener<JSONObject>() {
                @Override
                public void onResponse(JSONObject response) {
                    try {
                        // Obter o ID do local e da categoria do evento
                        int localId = response.getInt("local_id");
                        int categoriaId = response.getInt("categoria_id");

                        // Fazer novos pedidos para obter os nomes do local e da categoria
                        JsonObjectRequest localReq = new JsonObjectRequest(Request.Method.GET, BASE_URL(ip) + "locais/" + localId, null, new Response.Listener<JSONObject>() {
                            @Override
                            public void onResponse(JSONObject localResponse) {
                                try {
                                    String nomeLocal = localResponse.getString("nome");
                                    response.put("nome_local", nomeLocal);

                                    JsonObjectRequest categoriaReq = new JsonObjectRequest(Request.Method.GET, BASE_URL(ip) + "categorias/" + categoriaId, null, new Response.Listener<JSONObject>() {
                                        @Override
                                        public void onResponse(JSONObject categoriaResponse) {
                                            try {
                                                String nomeCategoria = categoriaResponse.getString("nome");
                                                response.put("nome_categoria", nomeCategoria);
                                                listener.onResponse(response);
                                            } catch (JSONException e) {
                                                e.printStackTrace();
                                                errorListener.onErrorResponse(new VolleyError("Erro ao processar resposta da categoria"));
                                            }
                                        }
                                    }, errorListener);

                                    volleyQueue.add(categoriaReq);
                                } catch (JSONException e) {
                                    e.printStackTrace();
                                    errorListener.onErrorResponse(new VolleyError("Erro ao processar resposta do local"));
                                }
                            }
                        }, errorListener);

                        volleyQueue.add(localReq);
                    } catch (JSONException e) {
                        e.printStackTrace();
                        errorListener.onErrorResponse(new VolleyError("Erro ao processar resposta do evento"));
                    }
                }
            }, errorListener);

            volleyQueue.add(req);
        }
    }

    // Função para pesquisar eventos
    public void pesquisarEventos(Context context, String query, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        if (!isNetworkAvailable(context)) {
            Toast.makeText(context, "Sem ligação à internet", Toast.LENGTH_SHORT).show();
        } else {
            SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
            String ip = prefs.getString("ip", BaseIp);
            JsonObjectRequest req = new JsonObjectRequest(Request.Method.GET, BASE_URL(ip) + "eventos?search=" + query, null, listener, errorListener);
            volleyQueue.add(req);
        }
    }

    // Função para adicionar bilhetes ao carrinho
    public void adicionarBilheteCarrinho(Context context, int bilheteId, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        if (!isNetworkAvailable(context)) {
            Toast.makeText(context, "Sem ligação à internet", Toast.LENGTH_SHORT).show();
        } else {
            SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
            String ip = prefs.getString("ip", BaseIp);
            JsonObjectRequest req = new JsonObjectRequest(Request.Method.POST, BASE_URL(ip) + "carrinho/add/" + bilheteId, null, listener, errorListener);
            volleyQueue.add(req);
        }
    }

    // Função para ver o carrinho
    public void verCarrinho(Context context, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        if (!isNetworkAvailable(context)) {
            Toast.makeText(context, "Sem ligação à internet", Toast.LENGTH_SHORT).show();
        } else {
            SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
            String ip = prefs.getString("ip", BaseIp);
            JsonObjectRequest req = new JsonObjectRequest(Request.Method.GET, BASE_URL(ip) + "carrinho", null, listener, errorListener);
            volleyQueue.add(req);
        }
    }

    // Função para atualizar a quantidade de bilhetes no carrinho
    public void atualizarQuantidadeBilhete(Context context, int bilheteId, int quantidade, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        if (!isNetworkAvailable(context)) {
            Toast.makeText(context, "Sem ligação à internet", Toast.LENGTH_SHORT).show();
        } else {
            SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
            String ip = prefs.getString("ip", BaseIp);
            JSONObject jsonParams = new JSONObject();
            try {
                jsonParams.put("quantidade", quantidade);
            } catch (JSONException e) {
                e.printStackTrace();
            }
            JsonObjectRequest req = new JsonObjectRequest(Request.Method.PUT, BASE_URL(ip) + "carrinho/edit/" + bilheteId, jsonParams, listener, errorListener);
            volleyQueue.add(req);
        }
    }

    // Função para remover bilhetes do carrinho
    public void removerBilheteCarrinho(Context context, int bilheteId, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        if (!isNetworkAvailable(context)) {
            Toast.makeText(context, "Sem ligação à internet", Toast.LENGTH_SHORT).show();
        } else {
            SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
            String ip = prefs.getString("ip", BaseIp);
            JsonObjectRequest req = new JsonObjectRequest(Request.Method.DELETE, BASE_URL(ip) + "carrinho/remove/" + bilheteId, null, listener, errorListener);
            volleyQueue.add(req);
        }
    }

    // Função para adicionar eventos aos favoritos
    public void adicionarEventoFavoritos(Context context, int eventoId, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        if (!isNetworkAvailable(context)) {
            Toast.makeText(context, "Sem ligação à internet", Toast.LENGTH_SHORT).show();
        } else {
            SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
            String ip = prefs.getString("ip", BaseIp);
            JsonObjectRequest req = new JsonObjectRequest(Request.Method.POST, BASE_URL(ip) + "favoritos/add/" + eventoId, null, listener, errorListener);
            volleyQueue.add(req);
        }
    }

    // Função para remover eventos dos favoritos
    public void removerEventoFavoritos(Context context, int eventoId, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        if (!isNetworkAvailable(context)) {
            Toast.makeText(context, "Sem ligação à internet", Toast.LENGTH_SHORT).show();
        } else {
            SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
            String ip = prefs.getString("ip", BaseIp);
            JsonObjectRequest req = new JsonObjectRequest(Request.Method.DELETE, BASE_URL(ip) + "favoritos/remove/" + eventoId, null, listener, errorListener);
            volleyQueue.add(req);
        }
    }

    // Função para efetuar o checkout
    public void checkout(Context context, JSONObject checkoutData, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        if (!isNetworkAvailable(context)) {
            Toast.makeText(context, "Sem ligação à internet", Toast.LENGTH_SHORT).show();
        } else {
            SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
            String ip = prefs.getString("ip", BaseIp);
            JsonObjectRequest req = new JsonObjectRequest(Request.Method.POST, BASE_URL(ip) + "carrinho/checkout", checkoutData, listener, errorListener);
            volleyQueue.add(req);
        }
    }

    // Método para selecionar método de pagamento
    public void selecionarMetodoPagamento(Context context, String metodoId, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
        String ip = prefs.getString("ip", BaseIp);
        String url = BASE_URL(ip) + "pagamento/" + metodoId;
        JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST, url, null, listener, errorListener);
        volleyQueue.add(request);
    }

    // Método para ver favoritos offline
    public void verFavoritosOffline(Context context, SQLiteDatabase db, Response.Listener<JSONObject> listener) {
        Cursor cursor = db.query("favoritos", null, null, null, null, null, null);
        JSONObject response = new JSONObject();
        JSONArray favoritosArray = new JSONArray();

        if (cursor.moveToFirst()) {
            do {
                JSONObject favorito = new JSONObject();
                try {
                    int eventoId = cursor.getInt(cursor.getColumnIndex("evento_id"));
                    Cursor eventoCursor = db.query("eventos", null, "id=?", new String[]{String.valueOf(eventoId)}, null, null, null);
                    if (eventoCursor.moveToFirst()) {
                        favorito.put("id", eventoCursor.getInt(eventoCursor.getColumnIndex("id")));
                        favorito.put("titulo", eventoCursor.getString(eventoCursor.getColumnIndex("titulo")));
                        Cursor imagemCursor = db.query("imagens", null, "evento_id=?", new String[]{String.valueOf(eventoId)}, null, null, null);
                        if (imagemCursor.moveToFirst()) {
                            favorito.put("imagem", imagemCursor.getString(imagemCursor.getColumnIndex("nome")));
                        }
                        imagemCursor.close();
                    }
                    eventoCursor.close();
                    favoritosArray.put(favorito);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            } while (cursor.moveToNext());
        }
        cursor.close();

        try {
            response.put("favoritos", favoritosArray);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        listener.onResponse(response);
    }

    // Método para sincronizar favoritos
    public void sincronizarFavoritos(Context context, SQLiteDatabase db, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
        String ip = prefs.getString("ip", BaseIp);
        String url = BASE_URL(ip) + "sincronizar";
        Cursor cursor = db.query("favoritos", null, null, null, null, null, null);
        JSONArray favoritosArray = new JSONArray();

        if (cursor.moveToFirst()) {
            do {
                JSONObject favorito = new JSONObject();
                try {
                    favorito.put("id", cursor.getInt(cursor.getColumnIndex("id")));
                    favorito.put("evento_id", cursor.getInt(cursor.getColumnIndex("evento_id")));
                    favoritosArray.put(favorito);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            } while (cursor.moveToNext());
        }
        cursor.close();

        JSONObject requestBody = new JSONObject();
        try {
            requestBody.put("favoritos", favoritosArray);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST, url, requestBody, listener, errorListener);
        volleyQueue.add(request);
    }

    // Método para atualizar o perfil do utilizador
    public void atualizarPerfil(Context context, String profileId, Map<String, String> profileData, Response.Listener<JSONObject> listener, Response.ErrorListener errorListener) {
        SharedPreferences prefs = context.getSharedPreferences("IP", Context.MODE_PRIVATE);
        String ip = prefs.getString("ip", BaseIp);
        String url = BASE_URL(ip) + "profile/" + profileId;
        JSONObject requestBody = new JSONObject(profileData);

        JsonObjectRequest request = new JsonObjectRequest(Request.Method.PUT, url, requestBody, listener, errorListener);
        volleyQueue.add(request);
    }
}
package com.example.ticketgomobileapp;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.ImageView;
import android.widget.Toast;
import androidx.appcompat.widget.SearchView;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.example.ticketgomobileapp.adaptadores.EventosAdapter;
import com.example.ticketgomobileapp.modelos.Categoria;
import com.example.ticketgomobileapp.modelos.Evento;
import com.example.ticketgomobileapp.modelos.Local;
import com.example.ticketgomobileapp.modelos.Singleton;
import com.example.ticketgomobileapp.utils.AuthUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class MainActivity extends AppCompatActivity {

    private ImageView logoImageView;
    private SearchView searchView;
    private RecyclerView eventosRecyclerView;
    private EventosAdapter eventosAdapter;
    private List<Evento> eventoList;

    private Map<Integer, Local> localMap = new HashMap<>();
    private Map<Integer, Categoria> categoriaMap = new HashMap<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        logoImageView = findViewById(R.id.logoImageView);
        searchView = findViewById(R.id.searchView);
        eventosRecyclerView = findViewById(R.id.eventsRecyclerView);

        ImageView houseIconView = findViewById(R.id.houseIconView);
        ImageView heartIconView = findViewById(R.id.heartIconView);
        ImageView profileIconView = findViewById(R.id.profileIconView);

        // Configurar RecyclerView
        eventosRecyclerView.setLayoutManager(new LinearLayoutManager(this));
        eventoList = new ArrayList<>();
        eventosAdapter = new EventosAdapter(eventoList);
        eventosRecyclerView.setAdapter(eventosAdapter);

        // Carregar locais e categorias primeiro
        loadLocaisAndCategorias();

        // Configurar SearchView
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                searchEventos(query);
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                searchEventos(newText);
                return false;
            }
        });

        // Configurar ícones de navegação
        houseIconView.setOnClickListener(v -> {
            Intent intent = new Intent(MainActivity.this, MainActivity.class);
            startActivity(intent);
        });

        heartIconView.setOnClickListener(v -> {
            if (AuthUtils.isUserAuthenticated(this)) {
                Intent intent = new Intent(MainActivity.this, FavoritosActivity.class);
                startActivity(intent);
            } else {
                Intent intent = new Intent(MainActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });

        profileIconView.setOnClickListener(v -> {
            if (AuthUtils.isUserAuthenticated(this) == true) {
                Intent intent = new Intent(MainActivity.this, PerfilActivity.class);
                startActivity(intent);
            } else {
                Intent intent = new Intent(MainActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });
    }

    private void loadLocaisAndCategorias() {
        loadLocais();
        loadCategorias();
    }

    private void loadLocais() {
        Singleton.getInstance(this).verLocais(this, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                Log.d("API Response Locais", response.toString());
                try {
                    for (int i = 0; i < response.length(); i++) {
                        JSONObject localObject = response.getJSONObject(i);
                        int id = localObject.getInt("id");
                        String nome = localObject.getString("nome");
                        String morada = localObject.getString("morada");
                        String cidade = localObject.getString("cidade");
                        int capacidade = localObject.getInt("capacidade");
                        localMap.put(id, new Local(id, nome, morada, cidade, capacidade));
                    }
                    Log.d("LocalMap", localMap.toString());
                    // Após carregar locais, carregar eventos
                    loadEventosFromAPI();
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(MainActivity.this, "Erro ao carregar locais", Toast.LENGTH_SHORT).show();
                Log.e("Erro", "Erro ao carregar locais: " + error.getMessage());
            }
        });
    }
    public void loadImagens() {
        Singleton.getInstance(this).verImagens(this, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                try {
                    for (int i = 0; i < response.length(); i++) {
                        JSONObject imagemObject = response.getJSONObject(i);
                        int eventoId = imagemObject.getInt("evento_id");
                        String nomeImagem = imagemObject.getString("nome");

                        // Construa a URL da imagem
                        String imagemUrl = "http://10.0.2.2/TicketGo/backend/web/api/imagem/" + nomeImagem;

                        // Associe a imagem ao evento correspondente
                        for (Evento evento : eventoList) {
                            if (evento.getId() == eventoId) {
                                evento.setImagemUrl(imagemUrl);
                                break;
                            }
                        }
                    }
                    eventosAdapter.notifyDataSetChanged(); // Notifique o adaptador sobre as mudanças
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(MainActivity.this, "Erro ao carregar imagens", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void loadCategorias() {
        Singleton.getInstance(this).verCategorias(this, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                Log.d("API Response categorias", response.toString());
                try {
                    for (int i = 0; i < response.length(); i++) {
                        JSONObject categoriaObject = response.getJSONObject(i);
                        int id = categoriaObject.getInt("id");
                        String nome = categoriaObject.getString("nome");
                        String descricao = categoriaObject.getString("descricao");
                        categoriaMap.put(id, new Categoria(id, nome, descricao));
                    }
                    Log.d("CategoriaMap", categoriaMap.toString());
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(MainActivity.this, "Erro ao carregar categorias", Toast.LENGTH_SHORT).show();
                Log.e("Erro", "Erro ao carregar categorias: " + error.getMessage());
            }
        });
    }

    private void loadEventosFromAPI() {
        Singleton.getInstance(this).verEventos(this, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                Log.d("API Response Eventos", response.toString());
                try {
                    for (int i = 0; i < response.length(); i++) {
                        JSONObject eventoObject = response.getJSONObject(i);
                        int id = eventoObject.getInt("id");
                        String titulo = eventoObject.getString("titulo");
                        String descricao = eventoObject.getString("descricao");
                        String dataInicio = eventoObject.getString("datainicio");
                        String dataFim = eventoObject.getString("datafim");
                        int localId = eventoObject.getInt("local_id");
                        int categoriaId = eventoObject.getInt("categoria_id");

                        String local = localMap.containsKey(localId) ? localMap.get(localId).getNome() : "Local desconhecido";
                        String categoria = categoriaMap.containsKey(categoriaId) ? categoriaMap.get(categoriaId).getNome() : "Categoria desconhecida";

                        eventoList.add(new Evento(id, titulo, descricao, dataInicio, dataFim, local, categoria));
                    }
                    eventosAdapter.notifyDataSetChanged();
                    loadImagens(); // Carregar imagens após carregar eventos
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(MainActivity.this, "Erro ao carregar eventos", Toast.LENGTH_SHORT).show();
                Log.e("Erro", "Erro ao carregar eventos: " + error.getMessage());
            }
        });
    }

    private void searchEventos(String query) {
        Singleton.getInstance(this).pesquisarEventos(this, query, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {
                eventoList.clear(); // Limpa a lista antes de adicionar novos eventos
                try {
                    // Acesse o array de eventos dentro do objeto JSON
                    JSONArray eventosArray = response.getJSONArray("eventos");
                    for (int i = 0; i < eventosArray.length(); i++) {
                        JSONObject eventoObject = eventosArray.getJSONObject(i);
                        int id = eventoObject.getInt("id");
                        String titulo = eventoObject.getString("titulo");
                        String descricao = eventoObject.getString("descricao");
                        String dataInicio = eventoObject.getString("datainicio");
                        String dataFim = eventoObject.getString("datafim");
                        String localName = eventoObject.getString("nome_local");
                        String categoriaName = eventoObject.getString("nome_categoria");

                        // Adiciona o evento à lista
                        eventoList.add(new Evento(id, titulo, descricao, dataInicio, dataFim, localName, categoriaName));
                    }
                    Log.d("EventoList", eventoList.toString()); // Log para verificar a lista de eventos
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(MainActivity.this, "Erro ao processar dados dos eventos", Toast.LENGTH_SHORT).show();
                }
                eventosAdapter.notifyDataSetChanged(); // Notifica o adapter sobre a mudança na lista
                if (eventoList.isEmpty()) {
                    Toast.makeText(MainActivity.this, "Nenhum evento encontrado", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(MainActivity.this, "Erro ao pesquisar eventos", Toast.LENGTH_SHORT).show();
                Log.e("Erro", "Erro ao pesquisar eventos: " + error.getMessage());
            }
        });
    }
}
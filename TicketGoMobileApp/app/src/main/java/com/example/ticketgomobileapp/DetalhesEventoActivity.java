package com.example.ticketgomobileapp;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.bumptech.glide.Glide;
import com.example.ticketgomobileapp.modelos.Singleton;
import com.example.ticketgomobileapp.utils.AuthUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Set;

public class DetalhesEventoActivity extends AppCompatActivity {

    private TextView nomeEventoTextView;
    private TextView descricaoEventoTextView;
    private TextView localEventoTextView;
    private TextView dataEventoTextView;
    private ImageView eventImage1; // Para a imagem do evento

    private ImageView btnFavorite;
    private boolean isFavorito = false;

    Button btnAdicionarCarrinho;

    private Spinner zonasSpinner; // Inicialização do Spinner
    private Map<Integer, String> zonasPrecos;

    @SuppressLint("MissingInflatedId")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detalhesevento);

        ImageView houseIconView = findViewById(R.id.houseIconView3);
        ImageView heartIconView = findViewById(R.id.heartIconView3);
        ImageView profileIconView = findViewById(R.id.profileIconView3);
        ImageView cartIconView = findViewById(R.id.cartIconView3);

        btnAdicionarCarrinho=findViewById(R.id.btnAddToCart);

        btnAdicionarCarrinho.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

            }
        });



        // Inicializar as views
        nomeEventoTextView = findViewById(R.id.nomeEvento);
        descricaoEventoTextView = findViewById(R.id.descricaoEvento);
        localEventoTextView = findViewById(R.id.localEvento);
        dataEventoTextView = findViewById(R.id.dataEvento);
        eventImage1 = findViewById(R.id.eventImage1); // Inicialize o ImageView
        zonasSpinner = findViewById(R.id.zonasSpinner); // Inicialize o Spinner
        btnFavorite = findViewById(R.id.btnFavorite);

        houseIconView.setOnClickListener(v -> {
            Intent intent = new Intent(DetalhesEventoActivity.this, MainActivity.class);
            startActivity(intent);
        });

        heartIconView.setOnClickListener(v -> {
            SharedPreferences sharedPreferences1 = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
            boolean isAuthenticated1 = sharedPreferences1.getBoolean("is_authenticated", false);
            Intent intent;
            if (isAuthenticated1) {
                intent = new Intent(DetalhesEventoActivity.this, FavoritosActivity.class);
            } else {
                intent = new Intent(DetalhesEventoActivity.this, LoginActivity.class);
            }
            startActivity(intent);
        });

        profileIconView.setOnClickListener(v -> {
            SharedPreferences sharedPreferences1 = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
            boolean isAuthenticated1 = sharedPreferences1.getBoolean("is_authenticated", false);
            Intent intent;
            if (isAuthenticated1) {
                intent = new Intent(DetalhesEventoActivity.this, PerfilActivity.class);
            } else {
                intent = new Intent(DetalhesEventoActivity.this, LoginActivity.class);
            }
            startActivity(intent);
        });

        cartIconView.setOnClickListener(v -> {
            SharedPreferences sharedPreferences1 = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
            boolean isAuthenticated1 = sharedPreferences1.getBoolean("is_authenticated", false);
            Intent intent;
            if (isAuthenticated1) {
                intent = new Intent(DetalhesEventoActivity.this, CarrinhoActivity.class);
            } else {
                intent = new Intent(DetalhesEventoActivity.this, LoginActivity.class);
            }
            startActivity(intent);
        });


        // Obter o ID do evento passado pela Intent
        int eventoId = getIntent().getIntExtra("evento_id", -1);
        if (eventoId != -1) {
            carregarDetalhesEvento(eventoId);
            carregarZonas(eventoId); // Chame para carregar as zonas
            verificarFavorito(eventoId);
        } else {
            Toast.makeText(this, "Evento não encontrado", Toast.LENGTH_SHORT).show();
            finish();
        }

        btnFavorite.setOnClickListener(v -> {
            Log.d("DetalhesEventoActivity", "Botão de favorito clicado");
            if (isFavorito) {
                removerFavorito(eventoId);
            } else {
                adicionarFavorito(eventoId);
            }
        });
    }

    private void carregarDetalhesEvento(int eventoId) {
        Singleton.getInstance(this).verDetalhesEvento(this, eventoId, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {
                Log.d("DetalhesEventoActivity", "Resposta: " + response.toString());
                try {
                    // Exibir as informações do evento
                    nomeEventoTextView.setText(response.getString("titulo"));
                    descricaoEventoTextView.setText(response.getString("descricao"));
                    localEventoTextView.setText(response.getString("nome_local"));
                    dataEventoTextView.setText(response.getString("datainicio"));

                    // Carregar a imagem do evento
                    carregarImagemEvento(eventoId); // Chame um método separado para carregar a imagem

                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(DetalhesEventoActivity.this, "Erro ao carregar detalhes do evento", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(DetalhesEventoActivity.this, "Erro na requisição", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void carregarZonas(int eventoId) {
        Singleton.getInstance(this).verZonas(this, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                zonasPrecos = new HashMap<>();
                Set<String> zonasSet = new HashSet<>(); // Usar um Set para evitar duplicatas

                try {
                    for (int i = 0; i < response.length(); i++) {
                        JSONObject zonaObject = response.getJSONObject(i);
                        int zonaId = zonaObject.getInt("id");
                        String lugar = zonaObject.getString("lugar");

                        // Log para verificar as zonas carregadas
                        Log.d("Zona Carregada", "ID: " + zonaId + ", Lugar: " + lugar);

                        // Agora, para cada zona, vamos buscar os bilhetes
                        buscarBilhetesPorZona(zonaId, lugar, zonasSet, eventoId); // Passar o eventoId
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(DetalhesEventoActivity.this, "Erro ao carregar zonas", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(DetalhesEventoActivity.this, "Erro ao carregar zonas", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void buscarBilhetesPorZona(int zonaId, String lugar, Set<String> zonasSet, int eventoId) {
        Singleton.getInstance(this).verBilhetes(this, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                try {
                    boolean found = false; // Para verificar se encontramos bilhetes para a zona

                    for (int i = 0; i < response.length(); i++) {
                        JSONObject bilheteObject = response.getJSONObject(i);
                        int bilheteEventoId = bilheteObject.getInt("evento_id");
                        int bilheteZonaId = bilheteObject.getInt("zona_id"); // Obtenha o zona_id do bilhete

                        // Verifica se o bilhete pertence ao evento específico e à zona
                        if (bilheteEventoId == eventoId && bilheteZonaId == zonaId) {
                            double preco = bilheteObject.getDouble("precounitario");
                            // Armazena o preço e o lugar
                            zonasSet.add("Lugar: " + lugar + ", Preço: " + preco);
                            found = true; // Encontramos pelo menos um bilhete para esta zona
                        }
                    }

                    // Log para verificar se encontramos bilhetes para a zona
                    if (found) {
                        Log.d("Bilhetes", "Bilhetes encontrados para a zona: " + lugar);
                    } else {
                        Log.d("Bilhetes", "Nenhum bilhete encontrado para a zona: " + lugar);
                    }

                    // Atualiza o Spinner com as zonas e preços
                    List<String> zonasList = new ArrayList<>(zonasSet); // Converte o Set para List
                    ArrayAdapter<String> adapter = new ArrayAdapter<>(DetalhesEventoActivity.this,
                            android.R.layout.simple_spinner_item, zonasList);
                    adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                    zonasSpinner.setAdapter(adapter);

                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(DetalhesEventoActivity.this, "Erro ao carregar preços dos bilhetes", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(DetalhesEventoActivity.this, "Erro ao carregar bilhetes", Toast.LENGTH_SHORT).show();
            }
        });
    }

    // Método para carregar a imagem do evento
    private void carregarImagemEvento(int eventoId) {
        Singleton.getInstance(this).verImagens(this, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                try {
                    String imagemUrl = null;

                    for (int i = 0; i < response.length(); i++) {
                        JSONObject imagemObject = response.getJSONObject(i);
                        if (imagemObject.getInt("evento_id") == eventoId) {
                            String nomeImagem = imagemObject.getString("nome");
                            imagemUrl = "http://10.0.2.2/TicketGoAPI/backend/web/imagem/" + nomeImagem;
                            break; // Encontrou a imagem, pode sair do loop
                        }
                    }

                    // Carregar a imagem se encontrada
                    if (imagemUrl != null) {
                        Glide.with(DetalhesEventoActivity.this)
                                .load(imagemUrl)
                                .into(eventImage1);
                    } else {
                        Toast.makeText(DetalhesEventoActivity.this, "Imagem não encontrada", Toast.LENGTH_SHORT).show();
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(DetalhesEventoActivity.this, "Erro ao carregar imagens", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(DetalhesEventoActivity.this, "Erro ao carregar imagens", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void verificarFavorito(int eventoId) {
        // Verifique se o evento está na lista de favoritos (Exemplo usando SharedPreferences)
        SharedPreferences sharedPreferences = getSharedPreferences("Favoritos", MODE_PRIVATE);
        isFavorito = sharedPreferences.getBoolean("favorito_" + eventoId, false);
    }

    private void adicionarFavorito(int eventoId) {
        // Adicionar evento à lista de favoritos
        SharedPreferences sharedPreferences = getSharedPreferences("Favoritos", MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putBoolean("favorito_" + eventoId, true);
        editor.apply();

        // Atualizar o estado de favorito
        isFavorito = true;

        // Exibir mensagem de sucesso
        Toast.makeText(this, "Evento adicionado aos favoritos com sucesso!", Toast.LENGTH_SHORT).show();
    }

    private void removerFavorito(int eventoId) {
        // Remover evento da lista de favoritos
        SharedPreferences sharedPreferences = getSharedPreferences("Favoritos", MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putBoolean("favorito_" + eventoId, false);
        editor.apply();

        // Atualizar o estado de favorito
        isFavorito = false;

        // Exibir mensagem de sucesso
        Toast.makeText(this, "Evento removido dos favoritos com sucesso!", Toast.LENGTH_SHORT).show();
    }
}
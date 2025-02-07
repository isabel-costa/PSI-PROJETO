package com.example.ticketgomobileapp;

import android.annotation.SuppressLint;
import android.os.Bundle;
import android.util.Log;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.bumptech.glide.Glide;
import com.example.ticketgomobileapp.modelos.Singleton;

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

    private Spinner zonasSpinner; // Inicialização do Spinner
    private Map<Integer, String> zonasPrecos;

    @SuppressLint("MissingInflatedId")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detalhesevento);

        // Inicializar as views
        nomeEventoTextView = findViewById(R.id.nomeEvento);
        descricaoEventoTextView = findViewById(R.id.descricaoEvento);
        localEventoTextView = findViewById(R.id.localEvento);
        dataEventoTextView = findViewById(R.id.dataEvento);
        eventImage1 = findViewById(R.id.eventImage1); // Inicialize o ImageView
        zonasSpinner = findViewById(R.id.zonasSpinner); // Inicialize o Spinner

        ImageView houseIconView = findViewById(R.id.houseIconView);
        ImageView heartIconView = findViewById(R.id.heartIconView);
        ImageView profileIconView = findViewById(R.id.profileIconView);

        // Obter o ID do evento passado pela Intent
        int eventoId = getIntent().getIntExtra("evento_id", -1);
        if (eventoId != -1) {
            carregarDetalhesEvento(eventoId);
            carregarZonas(eventoId); // Chame para carregar as zonas
        } else {
            Toast.makeText(this, "Evento não encontrado", Toast.LENGTH_SHORT).show();
            finish();
        }

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
                            imagemUrl = "http://localhost/SIS/TicketGo/backend/web/imagem/" + nomeImagem;
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
}
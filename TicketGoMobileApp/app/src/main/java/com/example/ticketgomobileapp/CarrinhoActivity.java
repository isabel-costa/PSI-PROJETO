package com.example.ticketgomobileapp;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.ticketgomobileapp.adaptadores.CarrinhoAdapter;
import com.example.ticketgomobileapp.modelos.LinhaCarrinho;
import com.example.ticketgomobileapp.utils.AuthUtils;


import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class CarrinhoActivity extends AppCompatActivity {

    private RecyclerView recyclerView;
    private CarrinhoAdapter adapter;
    private TextView totalAmount;
    private List<LinhaCarrinho> linhasCarrinho;
    private int carrinhoId = 1;  // Exemplo de ID de carrinho, você pode mudar conforme necessário
    @SuppressLint("MissingInflatedId")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_carrinho);

        ImageView houseIconView = findViewById(R.id.houseIconView);
        ImageView heartIconView = findViewById(R.id.heartIconView);
        ImageView profileIconView = findViewById(R.id.profileIconView);
        ImageView cartIconView = findViewById(R.id.cartIconView);

        houseIconView.setOnClickListener(v -> {
            Intent intent = new Intent(CarrinhoActivity.this, MainActivity.class);
            startActivity(intent);
        });

        heartIconView.setOnClickListener(v -> {
            SharedPreferences sharedPreferences1 = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
            boolean isAuthenticated1 = sharedPreferences1.getBoolean("is_authenticated", false);
            Intent intent;
            if (isAuthenticated1) {
                intent = new Intent(CarrinhoActivity.this, FavoritosActivity.class);
            } else {
                intent = new Intent(CarrinhoActivity.this, LoginActivity.class);
            }
            startActivity(intent);
        });

        profileIconView.setOnClickListener(v -> {
            SharedPreferences sharedPreferences1 = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
            boolean isAuthenticated1 = sharedPreferences1.getBoolean("is_authenticated", false);
            Intent intent;
            if (isAuthenticated1) {
                intent = new Intent(CarrinhoActivity.this, PerfilActivity.class);
            } else {
                intent = new Intent(CarrinhoActivity.this, LoginActivity.class);
            }
            startActivity(intent);
        });

        cartIconView.setOnClickListener(v -> {
            if (AuthUtils.isUserAuthenticated(this)) {
                Intent intent = new Intent(CarrinhoActivity.this, CarrinhoActivity.class);
                startActivity(intent);
            } else {
                Intent intent = new Intent(CarrinhoActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });

        SharedPreferences sharedPreferences = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
        boolean isAuthenticated = (sharedPreferences.getBoolean("is_authenticated", false));
        String authenticated = (sharedPreferences.getString("auth_key", ""));
        String userId = sharedPreferences.getString("user_id", "");
        String profileId=sharedPreferences.getString("profile_id","");
        Log.d("CarrinhoActivity", userId);
        Log.d("CarrinhoActivity",authenticated);
        Log.d("CarrinhoActivity",profileId);



        recyclerView = findViewById(R.id.recyclerViewCart);
        totalAmount = findViewById(R.id.totalAmount);
        Button btnFinalizePurchase = findViewById(R.id.btnFinalizePurchase);

        // Inicializando a lista de linhas do carrinho
        linhasCarrinho = new ArrayList<>();

        // Configurar o RecyclerView
        recyclerView.setLayoutManager(new LinearLayoutManager(this));

        // Carregar os itens do carrinho
        loadCarrinhoData(profileId,authenticated);

        // Configurar o botão de finalizar compra
        btnFinalizePurchase.setOnClickListener(v -> {
            // Aqui você pode colocar a lógica para finalizar a compra
            Intent intent= new Intent(CarrinhoActivity.this,CheckoutActivity.class);
            startActivity(intent);
            Toast.makeText(CarrinhoActivity.this, "A ir para o checkout!", Toast.LENGTH_SHORT).show();
        });
    }

    private void loadCarrinhoData(String profileId, String token) {
        String url = "http://10.0.2.2/TicketGo/backend/web/api/carrinho/get-carrinho?profile_id=" + profileId + "&token=" + token;

        StringRequest request = new StringRequest(Request.Method.GET, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            // Initialize total amount
                            double total = 0;

                            // Parse the response as a JSONObject
                            JSONObject carrinhoObject = new JSONObject(response);

                            // Extract the array of items in the cart (itens_no_carrinho)
                            JSONArray itensArray = carrinhoObject.getJSONArray("itens_no_carrinho");

                            // Loop through the array of cart items and extract their data
                            for (int i = 0; i < itensArray.length(); i++) {
                                JSONObject linhaObject = itensArray.getJSONObject(i);

                                // Create a new LinhaCarrinho object and populate it
                                LinhaCarrinho linha = new LinhaCarrinho();
                                linha.setId(linhaObject.getInt("linha_id"));
                                linha.setCarrinhoId(carrinhoObject.getInt("carrinho_id"));
                                JSONObject bilheteObject= linhaObject.getJSONObject("bilhete");
                                linha.setBilheteId(bilheteObject.getInt("bilhete_id"));
                                linha.setQuantidade(linhaObject.getInt("quantidade"));
                                JSONObject eventoObject= bilheteObject.getJSONObject("evento");
                                linha.setNomeEvento(eventoObject.getString("titulo"));
                                linha.setDataEvento(eventoObject.getString("data_inicio"));
                                linha.setPrecoUnitario(linhaObject.getDouble("preco_unitario"));
                                linha.setValorTotal(linhaObject.getDouble("valor_total"));

                                // Add the LinhaCarrinho object to the list
                                linhasCarrinho.add(linha);

                                // Update the total amount
                                total += linha.getValorTotal();
                            }

                            // Update the RecyclerView with the cart items
                            adapter = new CarrinhoAdapter(linhasCarrinho);

                            recyclerView.setAdapter(adapter);

                            // Update the total amount display
                            totalAmount.setText("€" + total);

                        } catch (Exception e) {
                            // Handle any exceptions that occur during parsing
                            e.printStackTrace();
                            Toast.makeText(CarrinhoActivity.this, "Erro ao carregar o carrinho", Toast.LENGTH_SHORT).show();
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                // Handle any error that occurs during the request
                Log.e("CarrinhoActivity", "Erro na requisição: " + error.getMessage());
                Toast.makeText(CarrinhoActivity.this, "Erro ao carregar o carrinho", Toast.LENGTH_SHORT).show();
            }
        });

        // Add the request to the Volley request queue
        Volley.newRequestQueue(this).add(request);
    }
}

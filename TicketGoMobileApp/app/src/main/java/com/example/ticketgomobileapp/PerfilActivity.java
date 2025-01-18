package com.example.ticketgomobileapp;

import android.app.DatePickerDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.material.textfield.TextInputEditText;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;

public class PerfilActivity extends AppCompatActivity {

    private TextInputEditText nameEditText;
    private TextInputEditText emailEditText;
    private EditText dataNascimentoEditText;
    private TextInputEditText nifEditText;
    private TextInputEditText moradaEditText;
    private Button saveButton;
    private Button dataNascimentoPickerButton;
    private Button logoutButton; // Novo botão de logout
    private ImageView houseIconView, heartIconView, profileIconView;
    private static final String PROFILE_URL = "https://your-api-url.com/profile"; // Substitua pelo endpoint da sua API

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_perfil);

        // Verificar autenticação
        SharedPreferences sharedPreferences = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
        boolean isAuthenticated = sharedPreferences.getBoolean("is_authenticated", false);

        if (!isAuthenticated) {
            // Redirecionar para login se não autenticado
            Intent intent = new Intent(PerfilActivity.this, LoginActivity.class);
            startActivity(intent);
            finish();
            return;
        }

        // Inicializar componentes
        nameEditText = findViewById(R.id.nameEditText);
        emailEditText = findViewById(R.id.emailEditText);
        dataNascimentoEditText = findViewById(R.id.dataNascimentoEditText);
        nifEditText = findViewById(R.id.nifEditText);
        moradaEditText = findViewById(R.id.moradaEditText);
        saveButton = findViewById(R.id.saveButton);
        dataNascimentoPickerButton = findViewById(R.id.dataNascimentoPickerButton);
        logoutButton = findViewById(R.id.logoutButton); // Inicializar o botão de logout
        houseIconView = findViewById(R.id.houseIconView);
        heartIconView = findViewById(R.id.heartIconView);
        profileIconView = findViewById(R.id.profileIconView);

        // Configurar DatePickerDialog
        dataNascimentoPickerButton.setOnClickListener(v -> {
            Calendar calendar = Calendar.getInstance();
            int year = calendar.get(Calendar.YEAR);
            int month = calendar.get(Calendar.MONTH);
            int day = calendar.get(Calendar.DAY_OF_MONTH);

            DatePickerDialog datePickerDialog = new DatePickerDialog(
                    PerfilActivity.this,
                    (view, year1, month1, dayOfMonth) -> {
                        String selectedDate = dayOfMonth + "/" + (month1 + 1) + "/" + year1;
                        dataNascimentoEditText.setText(selectedDate);
                    },
                    year, month, day);
            datePickerDialog.show();
        });

        // Configurar botão de salvar
        saveButton.setOnClickListener(v -> {
            String name = nameEditText.getText().toString().trim();
            String email = emailEditText.getText().toString().trim();
            String dataNascimento = dataNascimentoEditText.getText().toString().trim();
            String nif = nifEditText.getText().toString().trim();
            String morada = moradaEditText.getText().toString().trim();

            if (name.isEmpty() || email.isEmpty() || dataNascimento.isEmpty() || nif.isEmpty() || morada.isEmpty()) {
                Toast.makeText(PerfilActivity.this, "Por favor, preencha todos os campos.", Toast.LENGTH_SHORT).show();
            } else {
                atualizarPerfil(name, email, dataNascimento, nif, morada);
            }
        });

        // Configurar ícones de navegação
        houseIconView.setOnClickListener(v -> {
            Intent intent = new Intent(PerfilActivity.this, MainActivity.class);
            startActivity(intent);
        });

        heartIconView.setOnClickListener(v -> {
            if (isUserAuthenticated()) {
                Intent intent = new Intent(PerfilActivity.this, FavoritosActivity.class);
                startActivity(intent);
            } else {
                Intent intent = new Intent(PerfilActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });

        profileIconView.setOnClickListener(v -> {
            if (isUserAuthenticated()) {
                Intent intent = new Intent(PerfilActivity.this, PerfilActivity.class);
                startActivity(intent);
            } else {
                Intent intent = new Intent(PerfilActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });

        // Configurar botão de logout
        logoutButton.setOnClickListener(v -> {
            // Realizar o logout (remover dados de autenticação)
            SharedPreferences.Editor editor = sharedPreferences.edit();
            editor.putBoolean("is_authenticated", false);
            editor.apply();

            // Redirecionar para a tela de login
            Intent intent = new Intent(PerfilActivity.this, LoginActivity.class);
            startActivity(intent);
            finish();
        });

        // Carregar dados do perfil
        carregarDadosPerfil();
    }

    private void carregarDadosPerfil() {
        // Implementar lógica para carregar dados do perfil da API
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(
                Request.Method.GET,
                PROFILE_URL,
                null,
                response -> {
                    try {
                        String name = response.getString("name");
                        String email = response.getString("email");
                        String dataNascimento = response.getString("dataNascimento");
                        String nif = response.getString("nif");
                        String morada = response.getString("morada");

                        nameEditText.setText(name);
                        emailEditText.setText(email);
                        dataNascimentoEditText.setText(dataNascimento);
                        nifEditText.setText(nif);
                        moradaEditText.setText(morada);
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                },
                error -> Toast.makeText(PerfilActivity.this, "Erro ao carregar dados do perfil", Toast.LENGTH_SHORT).show()
        );
        requestQueue.add(jsonObjectRequest);
    }

    private void atualizarPerfil(String name, String email, String dataNascimento, String nif, String morada) {
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        Map<String, String> params = new HashMap<>();
        params.put("name", name);
        params.put("email", email);
        params.put("dataNascimento", dataNascimento);
        params.put("nif", nif);
        params.put("morada", morada);

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(
                Request.Method.PUT,
                PROFILE_URL,
                new JSONObject(params),
                response -> Toast.makeText(PerfilActivity.this, "Perfil atualizado com sucesso!", Toast.LENGTH_SHORT).show(),
                error -> Toast.makeText(PerfilActivity.this, "Erro ao atualizar perfil", Toast.LENGTH_SHORT).show()
        );

        requestQueue.add(jsonObjectRequest);
    }

    private boolean isUserAuthenticated() {
        SharedPreferences sharedPreferences = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
        return sharedPreferences.getBoolean("is_authenticated", false);
    }
}

package com.example.ticketgomobileapp.adaptadores;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.example.ticketgomobileapp.R;
import com.example.ticketgomobileapp.modelos.LinhaCarrinho;

import java.util.List;

public class CarrinhoAdapter extends RecyclerView.Adapter<CarrinhoAdapter.CarrinhoViewHolder> {

    private List<LinhaCarrinho> linhasCarrinho;

    public CarrinhoAdapter(List<LinhaCarrinho> linhasCarrinho) {
        this.linhasCarrinho = linhasCarrinho;
    }

    @Override
    public CarrinhoViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_cart, parent, false);
        return new CarrinhoViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(CarrinhoViewHolder holder, int position) {
        LinhaCarrinho linha = linhasCarrinho.get(position);

        // Atualizando as TextViews com os dados da linha do carrinho
        holder.eventName.setText(linha.getNomeEvento());  // Altere conforme o nome correto do evento
        holder.eventDate.setText(linha.getDataEvento());  // Adicione lógica para data do evento se necessário
        holder.eventPrice.setText("€" + linha.getPrecoUnitario());
        holder.quantityText.setText(String.valueOf(linha.getQuantidade()));

        // A lógica para atualizar a quantidade ou excluir o item pode ser adicionada aqui
        holder.btnDecrease.setOnClickListener(v -> {
            if (linha.getQuantidade() > 1) {
                linha.setQuantidade(linha.getQuantidade() - 1);
                holder.quantityText.setText(String.valueOf(linha.getQuantidade()));
                // Aqui você pode também atualizar o valor total ou refazer cálculos
            }
        });

        holder.btnIncrease.setOnClickListener(v -> {
            linha.setQuantidade(linha.getQuantidade() + 1);
            holder.quantityText.setText(String.valueOf(linha.getQuantidade()));
            // Aqui também pode-se atualizar o valor total
        });

        holder.trashIcon.setOnClickListener(v -> {


            linhasCarrinho.remove(position);  // Remover o item da lista
            notifyItemRemoved(position);  // Notificar que o item foi removido
        });
    }

    @Override
    public int getItemCount() {
        return linhasCarrinho.size();
    }

    public class CarrinhoViewHolder extends RecyclerView.ViewHolder {
        public TextView eventName, eventDate, eventPrice, quantityText;
        public Button btnDecrease, btnIncrease;
        public ImageView eventImage, trashIcon;

        public CarrinhoViewHolder(View view) {
            super(view);
            eventName = view.findViewById(R.id.eventName);
            eventDate = view.findViewById(R.id.eventDate);
            eventPrice = view.findViewById(R.id.eventPrice);
            quantityText = view.findViewById(R.id.quantityText);
            btnDecrease = view.findViewById(R.id.btnDecrease);
            btnIncrease = view.findViewById(R.id.btnIncrease);
            eventImage = view.findViewById(R.id.eventImage);
            trashIcon = view.findViewById(R.id.trashIcon);
        }
    }
}
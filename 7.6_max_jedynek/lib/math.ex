defmodule Genetic.Math do

  def getRandomFloat() do
    random = :crypto.strong_rand_bytes(1) |> :erlang.binary_to_list |> Enum.at(0)
    random / 256
  end

end
